<?php
/**
 * Class Refund
 */

namespace CheckoutFinland\SDK\Request;

use CheckoutFinland\SDK\Model\CallbackUrl;
use CheckoutFinland\SDK\Model\RefundItem;
use CheckoutFinland\SDK\Util\JsonSerializable;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class Refund
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=http-request-body
 *
 * @package CheckoutFinland\SDK\Request
 */
class Refund extends Request implements \JsonSerializable {

    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the assert() fails.
     */
    public function validate() {
        $props = get_object_vars( $this );

        if ( ! empty( $this->items ) ) {
            // Count the total amount of the payment.
            $items_total = array_reduce( $this->items, function( $carry = 0, ?RefundItem $item = null ) {
                if ( $item === null ) {
                    return $carry;
                }
                return $item->getAmount() + $carry;
            } );

            // Validate items.
            array_walk( $this->items, function( RefundItem $item ) {
                $item->validate();
            } );
        }
        else {
            $items_total = $this->amount;
        }

        v::key( 'amount', v::notEmpty()->intVal()->equals( $items_total ) )
        ->assert( $props );

        // Validate the callback urls.
        $this->callbackUrls->validate();
    }

    /**
     * Total amount to refund, in currency's minor units.
     *
     * @var int
     */
    protected $amount;

    /**
     * Array of items to refund. Use only for Shop-in-Shop payments.
     *
     * @var RefundItem[]
     */
    protected $items;

    /**
     * Which urls to ping after the refund has been processed.
     *
     * A single callbackurl object holding the success and cancellation urls.
     *
     * @var CallbackUrl
     */
    protected $callbackUrls;

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : int {
        return $this->amount;
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     *
     * @return Refund Return self to enable chaining.
     */
    public function setAmount( ?int $amount ) : Refund {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return RefundItem[]
     */
    public function getItems() : array {
        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param RefundItem[] $items The
     *
     * @return Refund Return self to enable chaining.
     */
    public function setItems( ?array $items ) : Refund {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the callback urls.
     *
     * @return CallbackUrl
     */
    public function getCallbackUrls() : CallbackUrl {
        return $this->callbackUrls;
    }

    /**
     * Set the callback urls.
     *
     * @param CallbackUrl $callbackUrls The callback url instance holding success and cancel urls.
     *
     * @return Refund Return self to enable chaining.
     */
    public function setCallbackUrls( ?CallbackUrl $callbackUrls ) : Refund {
        $this->callbackUrls = $callbackUrls;

        return $this;
    }
}