<?php
/**
 * Class EmailRefund
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Interfaces\RequestInterface;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\RefundItem;
use OpMerchantServices\SDK\Util\JsonSerializable;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class EmailRefund
 *
 * @see https://checkoutfinland.github.io/psp-api/#/examples?id=email-refund-request-body
 *
 * @package OpMerchantServices\SDK\Request
 */
class EmailRefundRequest extends RefundRequest
{

    /**
     * Email to which the refund message will be sent.
     *
     * @var string
     */
    protected $email;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the validate() fails.
     */
    public function validate()
    {
        parent::validate();

        v::notEmpty()->email()->validate($this->email);
    }

    /**
     * Get the email.
     *
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Set the email.
     *
     * @param string $email
     *
     * @return EmailRefundRequest Return self to enable chaining.
     */
    public function setEmail(?string $email) : EmailRefundRequest
    {
        $this->email = $email;

        return $this;
    }
}
