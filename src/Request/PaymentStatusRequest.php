<?php

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Interfaces\RequestInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class PaymentStatusRequest
 *
 * @package OpMerchantServices\SDK\Request
 */
class PaymentStatusRequest implements RequestInterface
{

    /**
     * Payment transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the validate() fails.
     */
    public function validate()
    {
        v::notEmpty()->stringType()->validate($this->transactionId);
    }

    /**
     * Get the transaction id.
     *
     * @return string
     */
    public function getTransactionId() : string
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     *
     * @return PaymentStatusRequest Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId) : PaymentStatusRequest
    {
        $this->transactionId = $transactionId;

        return $this;
    }
}
