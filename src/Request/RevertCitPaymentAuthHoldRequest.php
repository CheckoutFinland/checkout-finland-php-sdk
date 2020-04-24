<?php
/**
 * Class RevertCitPaymentAuthHoldRequest
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Interfaces\RequestInterface;
use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Util\JsonSerializable;

/**
 * Class RevertCitPaymentAuthHoldRequest
 *
 * @package OpMerchantServices\SDK\Request
 */
class RevertCitPaymentAuthHoldRequest implements \JsonSerializable, RequestInterface
{
    use JsonSerializable;

    /**
     * Payment transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        if (empty($this->transactionId)) {
            throw new ValidationException('Transaction id is empty');
        }

        return true;
    }

    /**
     * Get the transaction id.
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     *
     * @return RevertCitPaymentAuthHoldRequest Return self to enable chaining.
     */
    public function setTransactionId(?string $transactionId): RevertCitPaymentAuthHoldRequest
    {
        $this->transactionId = $transactionId;

        return $this;
    }
}
