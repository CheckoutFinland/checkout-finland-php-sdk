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
     * The error code texts.
     *
     * @var array
     */
    protected $responses = [
        '400' => [
            'text'        => 'Bad Request',
            'description' => 'The request was unacceptable, probably due to missing a required parameter.',
        ],
        '401' => [
            'text'        => 'Unauthorized',
            'description' => 'HMAC calculation failed or Merchant has no access to this feature.',
        ],
        '404' => [
            'text'        => 'Not Found',
            'description' => 'The requested resource doesn\'t exist.',
        ],
        '422' => [
            'text'        => 'Unprocessable Entity',
            'description' => 'The requested method is not supported.',
        ],
    ];

    /**
     * Override the constructor to define more
     * specific errors for API transactions.
     *
     * @param null            $message  Exception message.
     * @param int             $code     User defined exception code.
     * @param \Exception|null $previous Previous exception.
     */
    public function __construct( $message = null, $code = 0, \Exception $previous = null ) {
        $this->message   = $message;
        $this->code      = $code;
        $this->$previous = $previous;

        // If the code matches predefined codes,
        // format the message accordingly.
        $response = $this->responses[ $this->code ] ?? null;

        if ( $response ) {
            $this->message = $response['text'] . ' - ' . $response['description'];
        }
    }
}