<?php
/**
 * Class Client
 */

namespace CheckoutFinland\SDK;

use CheckoutFinland\SDK\Model\Provider;
use CheckoutFinland\SDK\Request\Payment;
use CheckoutFinland\SDK\Response\Payment as PaymentResponse;
use CheckoutFinland\SDK\Util\Signature;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Log\LoggerInterface;
use CheckoutFinland\SDK\Exception\PaymentRequestException;
use CheckoutFinland\SDK\Exception\PaymentProvidersRequestException;
use CheckoutFinland\SDK\Exception\HmacException;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class Client
 *
 * The client is the connector class for the API.
 *
 * @package CheckoutFinland\SDK
 */
class Client {

    /**
     * The merchant id.
     *
     * @var int
     */
    protected $merchantId;

    /**
     * Get the merchant id.
     *
     * @return int
     */
    public function getMerchantId() : ?int {

        return $this->merchantId;
    }

    /**
     * Set the merchant id.
     *
     * @param int $merchantId The merchant id.
     * @return Client Return self to enable chaining.
     */
    public function setMerchantId( int $merchantId ) : Client {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Get the merchant secret key.
     *
     * @return string
     */
    public function getSecretKey() : ?string {

        return $this->secretKey;
    }

    /**
     * Set the merchant secret key.
     *
     * @param string $secretKey The secrect key.
     * @return Client Return self to enable chaining.
     */
    public function setSecretKey( string $secretKey ) : Client {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * The merchant secret key.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * The Guzzle HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http_client;

    /**
     * The Checkout Finland API endpoint.
     */
    const API_ENDPOINT = 'https://api.checkout.fi';

    /**
     * Client constructor.
     *
     * @param int    $merchantId The merchant.
     * @param string $secretKey  The secret key.
     * @param array $args {
     *      Optional. Array of additional arguments.
     *
     *      @type float           $timeout          A timeout value in seconds for the GuzzleHTTP client.
     *      @type LoggerInterface $logger           A PSR-3 logger instance.
     *                                              See: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
     *      @type string          $message_format   The format for logger messages.
     *                                              See: https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php#L9
     */
    public function __construct( int $merchantId, string $secretKey, $args = [] ) {
        $this->setMerchantId( $merchantId );
        $this->setSecretKey( $secretKey );

        $stack = $this->createLoggerStack( $args );

        $this->http_client = new GuzzleHttpClient(
            [
                'headers'  => [],
                'base_uri' => self::API_ENDPOINT,
                'timeout'  => $args['timeout'] ?? 5,
                'handler'  => $stack,
            ]
        );
    }

    /**
     * Returns a handler stack containing a logger middleware
     * or an empty stack if no logger was set.
     *
     * @param array $args Passed client arguments.
     *
     * @return HandlerStack
     */
    private function createLoggerStack( array $args ) {
        if ( empty( $args['logger'] ) ) {
            return HandlerStack::create();
        }

        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $args['logger'],
                new MessageFormatter( $args['message_format'] ?? '{uri}: {req_body} - {res_body}' )
            )
        );
        return $stack;
    }

    /**
     * Format request headers.
     *
     * @param string $method The request method. GET or POST.
     *
     * @return array
     */
    protected function getHeaders( string $method ) {
        $datetime = new \DateTime();

        $headers = [
            'checkout-account'   => $this->merchantId,
            'checkout-algorithm' => 'sha256',
            'checkout-method'    => strtoupper( $method ),
            'checkout-nonce'     => uniqid( true ),
            'checkout-timestamp' => $datetime->format( 'Y-m-d\TH:i:s.u\Z' ),
            'content-type'       => 'application/json; charset=utf-8'
        ];
        return $headers;
    }

    /**
     * Get a list of payment providers.
     *
     * @param int $amount Purchase amount in currency's minor unit.
     * @return Provider[]
     *
     * @throws HmacException                    Thrown if HMAC calculation fails for responses.
     * @throws PaymentProvidersRequestException Thrown for erroneous requests.
     */
    public function getPaymentProviders( int $amount = null ) {
        try {
            $uri = new Uri( '/merchants/payment-providers' );

            $headers = $this->getHeaders( 'GET' );
            $mac     = $this->calculateHmac( $headers );

            // Sign the request.
            $headers['signature'] = $mac;
            $request_params       = [
                'headers' => $headers,
            ];

            // Set the amount query parameter.
            if ( $amount !== null ) {
                $request_params['query'] = [
                    'amount' => $amount
                ];
            }

            $response = $this->http_client->get( $uri, $request_params );
            $body     = (string) $response->getBody();

            // Validate the signature.
            $headers = $this->reduce_headers( $response->getHeaders() );
            $this->validateHmac( $headers, $body, $headers['signature'] ?? '' );

            // Instantiate providers.
            $decoded   = json_decode( $body );
            $providers = array_map( function( $provider_data ) {
                return ( new Provider() )->bind_properties( $provider_data );
            }, $decoded );

            return $providers;
        }
        catch ( HmacException $e ) {
            throw $e;
        }
        catch ( \Exception $e ) {
            $code = $e->getCode();
            throw new PaymentProvidersRequestException( 'An error occurred while loading the payment provider list.', $code );
        }
    }

    /**
     * Create a payment request.
     *
     * @param Payment $payment A payment class instance.
     * @return PaymentResponse
     *
     * @throws NestedValidationException Thrown when the assert() fails.
     * @throws HmacException             Thrown if HMAC calculation fails for responses.
     * @throws PaymentRequestException   Thrown for erroneous requests.
     */
    public function createPayment( Payment $payment ) {
        $payment->validate();

        try {
            $uri      = new Uri( '/payments' );

            $headers = $this->getHeaders( 'POST' );
            $body    = json_encode( $payment, JSON_UNESCAPED_SLASHES );
            $mac     = $this->calculateHmac( $headers, $body );

            $headers['signature'] = $mac;

            $response = $this->http_client->post( $uri, [
                'headers' => $headers,
                'body'    => $body
            ] );
            $body     = (string) $response->getBody();

            // Handle header data and validate HMAC.
            $headers = $this->reduce_headers( $response->getHeaders() );
            $this->validateHmac( $headers, $body, $headers['signature'] ?? '' );

            $decoded          = json_decode( $body );
            $payment_response = ( new PaymentResponse() )
            ->setTransactionId( $decoded->transactionId ?? null )
            ->setHref( $decoded->href ?? null )
            ->setProviders( $decoded->providers ?? null );

            return $payment_response;
        }
        catch ( HmacException $e ) {
            throw $e;
        }
        catch ( \Exception $e ) {
            $code = $e->getCode();
            throw new PaymentRequestException( 'An error occurred creating the payment request.', $code );
        }
    }

    /**
     * The PSR message interface defines headers as
     * an associative array where every header key has
     * an array of values. This method reduces the values to one.
     *
     * @param array[][] $headers The respose headers.
     *
     * @return array
     */
    protected function reduce_headers( array $headers = [] ) {
        return array_map( function( $value ) {
            return $value[0] ?? $value;
        }, $headers );
    }

    /**
     * A proxy for the Signature class' static method
     * to be used via a client instance.
     *
     * @param array  $params The parameters.
     * @param string $body   The body.
     * @return string SHA-256 HMAC
     */
    protected function calculateHmac( array $params = [], string $body = '' ) {
        return Signature::calculateHmac( $params, $body, $this->secretKey );
    }

    /**
     * A proxy for the Signature class' static method
     * to be used via a client instance.
     *
     * @param array  $response  The response parameters.
     * @param string $body      The response body.
     * @param string $signature The response signature key.
     *
     * @throws HmacException
     */
    protected function validateHmac( array $response = [], string $body = '', string $signature = '' ) {
        Signature::validateHmac( $response, $body, $signature, $this->secretKey );
    }
}
