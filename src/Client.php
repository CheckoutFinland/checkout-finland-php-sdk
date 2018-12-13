<?php
/**
 * Class Client
 */

namespace CheckoutFinland\SDK;

use \CheckoutFinland\SDK\Exception;
use CheckoutFinland\SDK\Model\Provider;
use \CheckoutFinland\SDK\Request\Payment;
use \CheckoutFinland\SDK\Response\Payment as PaymentResponse;
use \GuzzleHttp\Psr7\Uri;
use \GuzzleHttp\HandlerStack;
use \GuzzleHttp\Middleware;
use \GuzzleHttp\MessageFormatter;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \Psr\Log\LoggerInterface;

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
    const API_ENPOINT = 'https://api.checkout.fi';

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
                'base_uri' => self::API_ENPOINT,
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
        $headers = [
            'checkout-account'   => $this->merchantId,
            'checkout-algorithm' => 'sha256',
            'checkout-method'    => strtoupper( $method ),
            'checkout-nonce'     => uniqid( true ),
            'checkout-timestamp' => date_format( date_create( '@'. time() ), 'c') . 'Z',
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
     * @throws Exception\PaymentProvidersRequestException An error is thrown for erroneous requests.
     */
    public function getPaymentProviders( int $amount = null ) {
        try {
            $uri      = new Uri( '/merchants/payment-providers' );

            $headers = $this->getHeaders( 'GET' );
            $mac     = $this->calculateHmac( $headers );

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
            $decoded  = json_decode( $body );

            $providers = array_map( function( $provider_data ) {
                return ( new Provider() )->bind_properties( $provider_data );
            }, $decoded );

            return $providers;
        }
        catch ( \Exception $e ) {
            $code = $e->getCode();
            throw new Exception\PaymentProvidersRequestException( 'An error occurred while loading the payment provider list.', $code );
        }
    }

    /**
     * Create a payment request.
     *
     * @param Payment $payment A payment class instance.
     * @return PaymentResponse
     *
     * @throws Exception\PropertyException       An error is thrown if payment properties are invalid.
     * @throws Exception\PaymentRequestException An error is thrown for erroneous requests.
     */
    public function createPayment( Payment $payment ) {
        $payment->validate();

        try {
            $uri      = new Uri( '/payments' );

            $headers = $this->getHeaders( 'POST' );
            $body    = $body = json_encode( $payment, JSON_UNESCAPED_SLASHES );
            $mac     = $this->calculateHmac( $headers, $body );

            $headers['signature'] = $mac;

            $response = $this->http_client->post( $uri, [
                'headers' => $headers,
                'body'    => $body
            ] );
            $body     = (string) $response->getBody();
            $decoded  = json_decode( $body );

            $payment_response = new PaymentResponse();
            $payment_response->bind_properties( $decoded );
            $payment_response->setProviders( $decoded->providers ?? null );

            return $payment_response;
        }
        catch ( \Exception $e ) {
            $code = $e->getCode();
            throw new Exception\PaymentRequestException( 'An error occurred creating the payment request.', $code );
        }
    }

    /**
     * Calculate Checkout HMAC
     *
     * @param array[string]|string  $params HTTP headers or query string
     * @param string                $body HTTP request body, empty string for GET requests
     * @return string SHA-256 HMAC
     */
    protected function calculateHmac( $params, string $body = '' ) {
        // Keep only checkout- params, more relevant for response validation.
        $includedKeys = array_filter( array_keys( $params ), function ( $key ) {
            return preg_match( '/^checkout-/', $key );
        });

        // Keys must be sorted alphabetically
        sort( $includedKeys, SORT_STRING );

        $hmacPayload = array_map(
            function ( $key ) use ( $params ) {
                return join(':', [ $key, $params[ $key ] ]);
            },
            $includedKeys
        );

        array_push( $hmacPayload, $body );

        return hash_hmac( 'sha256', join( "\n", $hmacPayload ) , $this->secretKey );
    }
}
