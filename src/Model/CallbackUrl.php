<?php
/**
 * Class CallbackUrl
 */

namespace CheckoutFinland\SDK\Model;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

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
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the assert() fails.
     */
    public function validate() {
        $props = get_object_vars( $this );

        v::key( 'success', v::notEmpty() )
        ->key( 'cancel', v::notEmpty() )
        ->assert( $props );
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
    public function getSuccess(): ?string {

        return $this->success;
    }

    /**
     * Set the success url.
     *
     * @param string $success
     * @return CallbackUrl Return self to enable chaining.
     */
    public function setSuccess( ?string $success ): CallbackUrl {
        $this->success = $success;

        return $this;
    }

    /**
     * Get the cancellation url.
     *
     * @return string
     */
    public function getCancel(): ?string {

        return $this->cancel;
    }

    /**
     * Set the cancellation url.
     *
     * @param string $cancel
     * @return CallbackUrl Return self to enable chaining.
     */
    public function setCancel( ?string $cancel ): CallbackUrl {
        $this->cancel = $cancel;

        return $this;
    }

}