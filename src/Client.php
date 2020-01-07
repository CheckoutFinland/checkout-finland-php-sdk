<?php
/**
 * Class Client
 */

namespace OpMerchantServices\SDK;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Provider;
use OpMerchantServices\SDK\Request\PaymentRequest;
use OpMerchantServices\SDK\Request\RefundRequest;
use OpMerchantServices\SDK\Request\EmailRefundRequest;
use OpMerchantServices\SDK\Response\PaymentResponse;
use OpMerchantServices\SDK\Response\RefundResponse;
use OpMerchantServices\SDK\Response\EmailRefundResponse;
use OpMerchantServices\SDK\Util\Signature;
use OpMerchantServices\SDK\Interfaces\RequestInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use OpMerchantServices\SDK\Exception\HmacException;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class Client
 *
 * The client is the connector class for the API.
 *
 * @package OpMerchantServices\SDK
 */
class Client
{

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
    public function getMerchantId() : ?int
    {

        return $this->merchantId;
    }

    /**
     * Set the merchant id.
     *
     * @param int $merchantId The merchant id.
     * @return Client Return self to enable chaining.
     */
    public function setMerchantId(int $merchantId) : Client
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Get the merchant secret key.
     *
     * @return string
     */
    public function getSecretKey() : ?string
    {

        return $this->secretKey;
    }

    /**
     * Set the merchant secret key.
     *
     * @param string $secretKey The secrect key.
     * @return Client Return self to enable chaining.
     */
    public function setSecretKey(string $secretKey) : Client
    {
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
     * Plugin version for the API.
     *
     * @var string
     */
    protected $cofPluginVersion;

    /**
     * @param string $cofPluginVersion
     * @return Client
     */
    public function setCofPluginVersion(string $cofPluginVersion): Client
    {
        $this->cofPluginVersion = $cofPluginVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getCofPluginVersion(): string
    {
        return $this->cofPluginVersion;
    }


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
     * @param string $cofPluginVersion Plugin version.
     * @param array $args {
     *      Optional. Array of additional arguments.
     *
     *      @type float           $timeout          A timeout value in seconds for the GuzzleHTTP client.
     *      @type LoggerInterface $logger           A PSR-3 logger instance.
     *                                              See: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
     *      @type string          $message_format   The format for logger messages.
     *                                              See: https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php#L9
     */
    public function __construct(int $merchantId, string $secretKey, string $cofPluginVersion, $args = [])
    {
        $this->setMerchantId($merchantId);
        $this->setSecretKey($secretKey);
        $this->setCofPluginVersion($cofPluginVersion);

        $stack = $this->createLoggerStack($args);

        $this->http_client = new GuzzleHttpClient(
            [
                'headers'  => [],
                'base_uri' => self::API_ENDPOINT,
                'timeout'  => $args['timeout'] ?? 10,
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
    private function createLoggerStack(array $args)
    {
        if (empty($args['logger'])) {
            return HandlerStack::create();
        }

        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $args['logger'],
                new MessageFormatter($args['message_format'] ?? '{uri}: {req_body} - {res_body}')
            )
        );
        return $stack;
    }

    /**
     * Format request headers.
     *
     * @param string $method         The request method. GET or POST.
     * @param string $transactionId  Checkout transaction ID when accessing single transaction
     *                               not required for a new payment request.
     *
     * @return array
     * @throws \Exception
     */
    protected function getHeaders(string $method, string $transactionId = null)
    {
        $datetime = new \DateTime();

        $headers = [
            'checkout-account'   => $this->merchantId,
            'checkout-algorithm' => 'sha256',
            'checkout-method'    => strtoupper($method),
            'checkout-nonce'     => uniqid(true),
            'checkout-timestamp' => $datetime->format('Y-m-d\TH:i:s.u\Z'),
            'cof-plugin-version' => $this->cofPluginVersion,
            'content-type'       => 'application/json; charset=utf-8',
        ];

        if (! empty($transactionId)) {
            $headers['checkout-transaction-id'] = $transactionId;
        }

        return $headers;
    }

    /**
     * Get a list of payment providers.
     *
     * @param int $amount Purchase amount in currency's minor unit.
     * @return Provider[]
     *
     * @throws HmacException       Thrown if HMAC calculation fails for responses.
     * @throws RequestException    A Guzzle HTTP request exception is thrown for erroneous requests.
     */
    public function getPaymentProviders(int $amount = null)
    {
        $uri = new Uri('/merchants/payment-providers');

        $headers = $this->getHeaders('GET');
        $mac     = $this->calculateHmac($headers);

        // Sign the request.
        $headers['signature'] = $mac;
        $request_params       = [
            'headers' => $headers,
        ];

        // Set the amount query parameter.
        if ($amount !== null) {
            $request_params['query'] = [
                'amount' => $amount
            ];
        }

        $response = $this->http_client->get($uri, $request_params);
        $body     = (string) $response->getBody();

        // Validate the signature.
        $headers = $this->reduceHeaders($response->getHeaders());
        $this->validateHmac($headers, $body, $headers['signature'] ?? '');

        // Instantiate providers.
        $decoded   = json_decode($body);
        $providers = array_map(function ($provider_data) {
            return ( new Provider() )->bindProperties($provider_data);
        }, $decoded);

        return $providers;
    }

    /**
     * Create a payment request.
     *
     * @param PaymentRequest $payment A payment class instance.
     *
     * @return PaymentResponse
     * @throws HmacException        Thrown if HMAC calculation fails for responses.
     * @throws RequestException     A Guzzle HTTP request exception is thrown for erroneous requests.
     * @throws ValidationException  Thrown if payment validation fails.
     */
    public function createPayment(PaymentRequest $payment)
    {
        $this->validateRequestItem($payment);

        $uri = new Uri('/payments');

        $payment_response = $this->post(
            $uri,
            $payment,
            /**
             * Create the response instance.
             *
             * @param mixed $decoded The decoded body.
             * @return PaymentResponse
             */
            function ($decoded) {
                return ( new PaymentResponse() )
                    ->setTransactionId($decoded->transactionId ?? null)
                    ->setHref($decoded->href ?? null)
                    ->setProviders($decoded->providers ?? null);
            }
        );

        return $payment_response;
    }

    /**
     * Refunds a payment by transaction ID.
     *
     * @see https://checkoutfinland.github.io/psp-api/#/?id=refund
     *
     * @param RefundRequest $refund        A refund instance.
     * @param string        $transactionID The transaction id.
     *
     * @return RefundResponse Returns a refund response after successful refunds.
     * @throws HmacException        Thrown if HMAC calculation fails for responses.
     * @throws RequestException     A Guzzle HTTP request exception is thrown for erroneous requests.
     * @throws ValidationException  Thrown if payment validation fails.
     */
    public function refund(RefundRequest $refund, string $transactionID = '') : RefundResponse
    {
        $this->validateRequestItem($refund);

        try {
            $uri = new Uri('/payments/' . $transactionID . '/refund');

            // This will throw an error if the refund is not created.
            $refund_response = $this->post(
                $uri,
                $refund,
                /**
                 * Create the response instance.
                 *
                 * @param mixed $decoded The decoded body.
                 * @return RefundResponse
                 */
                    function ($decoded) {
                        return ( new RefundResponse() )
                         ->setProvider($decoded->provider ?? null)
                         ->setStatus($decoded->status ?? null)
                         ->setTransactionId($decoded->transactionId ?? null);
                    },
                $transactionID
            );
        } catch (HmacException $e) {
            throw $e;
        }

        return $refund_response;
    }

    /**
     * Refunds a payment by transaction ID as an email refund.
     *
     * @see https://checkoutfinland.github.io/psp-api/#/?id=email-refund
     *
     * @param EmailRefundRequest $refund        An email refund instance.
     * @param string             $transactionID The transaction id.
     *
     * @return EmailRefundResponse Returns a refund response after successful refunds.
     * @throws HmacException       Thrown if HMAC calculation fails for responses.
     * @throws RequestException    A Guzzle HTTP request exception is thrown for erroneous requests.
     * @throws ValidationException Thrown if payment validation fails.
     */
    public function emailRefund(EmailRefundRequest $refund, string $transactionID = '') : EmailRefundResponse
    {
        $this->validateRequestItem($refund);

        try {
            $uri = new Uri('/payments/' . $transactionID . '/refund/email');

            // This will throw an error if the refund is not created.
            $refund_response = $this->post(
                $uri,
                $refund,
                /**
                 * Create the response instance.
                 *
                 * @param mixed $decoded The decoded body.
                 * @return EmailRefundResponse
                 */
                    function ($decoded) {
                        return ( new EmailRefundResponse() )
                         ->setProvider($decoded->provider ?? null)
                         ->setStatus($decoded->status ?? null)
                         ->setTransactionId($decoded->transactionId ?? null);
                    },
                $transactionID
            );
        } catch (HmacException $e) {
            throw $e;
        }

        return $refund_response;
    }

    /**
     * A wrapper for post requests.
     *
     * @param Uri               $uri            The uri for the request.
     * @param \JsonSerializable $data           The request payload.
     * @param callable          $callback       The callback method to run for the decoded response.
     *                                          If left empty, the response is returned.
     * @param string            $transactionId  Checkout transaction ID when accessing single transaction
     *                                          not required for a new payment request.
     *
     * @return mixed|ResponseInterface Callback return value or the response object.
     * @throws HmacException
     */
    protected function post(Uri $uri, \JsonSerializable $data, callable $callback = null, string $transactionId = null)
    {
        $headers = $this->getHeaders('POST', $transactionId);
        $body    = json_encode($data, JSON_UNESCAPED_SLASHES);
        $mac     = $this->calculateHmac($headers, $body);

        $headers['signature'] = $mac;

        $response = $this->http_client->post($uri, [
            'headers' => $headers,
            'body'    => $body
        ]);
        $body     = (string) $response->getBody();

        // Handle header data and validate HMAC.
        $headers = $this->reduceHeaders($response->getHeaders());
        $this->validateHmac($headers, $body, $headers['signature'] ?? '');

        if ($callback) {
            $decoded = json_decode($body);
            return call_user_func($callback, $decoded);
        }

        return $response;
    }

    /**
     * Validate a request item.
     *
     * Handle the Respect\Validation nested exception by
     * wrapping the messages into a validation exception.
     * The original Respect\Validation exceptions is accessible
     * by calling $e->getPrevious() for the thrown exception.
     *
     * @param RequestInterface $item A request instance.
     *
     * @throws ValidationException
     */
    protected function validateRequestItem(?RequestInterface $item)
    {
        if (method_exists($item, 'validate')) {
            try {
                $item->validate();
            } catch (NestedValidationException $e) {
                $message  = $e->getMainMessage();
                $messages = $e->getMessages();
                throw ( new ValidationException($message, $e->getCode(), $e) )
                    ->setMessages($messages);
            }
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
    protected function reduceHeaders(array $headers = [])
    {
        return array_map(function ($value) {
            return $value[0] ?? $value;
        }, $headers);
    }


    /**
     * A proxy for the Signature class' static method
     * to be used via a client instance.
     *
     * @param array  $params The parameters.
     * @param string $body   The body.
     * @return string SHA-256 HMAC
     */
    protected function calculateHmac(array $params = [], string $body = '')
    {
        return Signature::calculateHmac($params, $body, $this->secretKey);
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
    public function validateHmac(array $response = [], string $body = '', string $signature = '')
    {
        Signature::validateHmac($response, $body, $signature, $this->secretKey);
    }
}
