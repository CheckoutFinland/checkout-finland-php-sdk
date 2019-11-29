<?php
/**
 * Class CallbackUrl
 */

namespace OpMerchantServices\SDK\Model;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Util\JsonSerializable;

/**
 * Class CallbackUrl
 *
 * This class defines callback url details.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=callbackurl
 * @package OpMerchantServices\SDK\Model
 */
class CallbackUrl implements \JsonSerializable
{

    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['success'])) {
            throw new ValidationException('Success is empty');
        }

        if (empty($props['cancel'])) {
            throw new ValidationException('Cancel is empty');
        }

        return true;
    }

    /**
     * The success url.
     *
     * @var string
     */
    protected $success;

    /**
     * The cancellation url.
     *
     * @var string
     */
    protected $cancel;

    /**
     * Get the success url.
     *
     * @return string
     */
    public function getSuccess(): ?string
    {

        return $this->success;
    }

    /**
     * Set the success url.
     *
     * @param string $success
     * @return CallbackUrl Return self to enable chaining.
     */
    public function setSuccess(?string $success): CallbackUrl
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Get the cancellation url.
     *
     * @return string
     */
    public function getCancel(): ?string
    {

        return $this->cancel;
    }

    /**
     * Set the cancellation url.
     *
     * @param string $cancel
     * @return CallbackUrl Return self to enable chaining.
     */
    public function setCancel(?string $cancel): CallbackUrl
    {
        $this->cancel = $cancel;

        return $this;
    }
}
