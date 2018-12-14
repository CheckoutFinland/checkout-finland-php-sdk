<?php
/**
 * Class RequestException
 */

namespace CheckoutFinland\SDK\Exception;

/**
 * Class RequestException
 *
 * An abstraction for all request exceptions.
 *
 * @package CheckoutFinland\SDK\Exception
 */
abstract class RequestException extends \Exception {

    /**
     * The previous throwable used for the exception chaining.
     *
     * @var \Throwable $previous
     */
    protected $previous;

    /**
     * Override the constructor to define more
     * specific errors for API transactions.
     *
     * @param null       $message  Exception message.
     * @param int        $code     User defined exception code.
     * @param \Throwable $previous Previous exception.
     */
    public function __construct( $message = null, $code = 0, \Throwable $previous = null ) {
        $this->message  = $message;
        $this->code     = $code;
        $this->previous = $previous;
    }

    /**
     * Set the previous exception.
     *
     * @param \Throwable $previous
     *
     * @return RequestException Return self to enable chaining.
     */
    public function setPrevious( ?\Throwable $previous ): RequestException {
        $this->previous = $previous;

        return $this;
    }
}
