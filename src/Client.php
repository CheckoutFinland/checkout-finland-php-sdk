<?php
/**
 *
 */

namespace CheckoutFinland\SDK;

use CheckoutFinland\SDK\Request\Payment;
use Psr\Log\LoggerInterface;

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
    public function getMerchantId(): int {

        return $this->merchantId;
    }

    /**
     * Set the merchant id.
     *
     * @param int $merchantId
     */
    public function setMerchantId( int $merchantId ): void {
        $this->merchantId = $merchantId;
    }

    /**
     * Get the merchant secret key.
     *
     * @return string
     */
    public function getSecretKey(): string {

        return $this->secretKey;
    }

    /**
     * Set the merchant secret key.
     *
     * @param string $secretKey
     */
    public function setSecretKey( string $secretKey ): void {
        $this->secretKey = $secretKey;
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

        $stack = $this->create_logger_stack( $args );

        $this->http_client = new \GuzzleHttp\Client(
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
     * @return \GuzzleHttp\HandlerStack
     */
    private function create_logger_stack( array $args ) {
        if ( empty( $args['logger'] ) ) {
            return \GuzzleHttp\HandlerStack::create();
        }

        $stack = \GuzzleHttp\HandlerStack::create();
        $stack->push(
            \GuzzleHttp\Middleware::log(
                $args['logger'],
                new \GuzzleHttp\MessageFormatter( $args['message_format'] ?? '{uri}: {req_body} - {res_body}' )
            )
        );
        return $stack;
    }

    public function create_payment( Payment $payment ) {

    }
}
