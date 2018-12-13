<?php
/**
 * Class RefundRequestException
 */

namespace CheckoutFinland\SDK\Exception;

/**
 * Class RefundRequestException
 *
 * Thrown on erroneous refund requests.
 *
 * @package CheckoutFinland\SDK\Exception
 */
class RefundRequestException extends RequestException {

    /**
     * Override the error code texts.
     *
     * @var array
     */
    protected $responses = [
        '400' => [
            'text'        => 'Bad Request',
            'description' => 'Schema validation failed. Item refunds are available only for SiS refunds',
        ],
        '422' => [
            'text'        => 'Unprocessable Entity',
            'description' => 'Used payment method provider does not support refunds.',
        ],
    ];

}
