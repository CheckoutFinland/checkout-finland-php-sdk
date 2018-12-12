<?php
/**
 * Class CallbackUrl
 */

namespace CheckoutFinland\SDK\Model;

/**
 * Class CallbackUrl
 *
 * This class defines callback url details.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=callbackurl
 * @package CheckoutFinland\SDK\Model
 */
class CallbackUrl {

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
    public function getSuccess(): string {

        return $this->success;
    }

    /**
     * Set the success url.
     *
     * @param string $success
     */
    public function setSuccess( string $success ): void {

        $this->success = $success;
    }

    /**
     * Get the cancellation url.
     *
     * @return string
     */
    public function getCancel(): string {

        return $this->cancel;
    }

    /**
     * Set the cancellation url.
     *
     * @param string $cancel
     */
    public function setCancel( string $cancel ): void {

        $this->cancel = $cancel;
    }

}