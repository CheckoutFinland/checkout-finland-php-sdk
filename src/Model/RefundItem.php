<?php
/**
 * Class RefundItem
 */

namespace CheckoutFinland\SDK\Model;

use CheckoutFinland\SDK\Util\JsonSerializable;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class RefundItem
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=refunditem
 *
 * @package CheckoutFinland\SDK\Model
 */
class RefundItem implements \JsonSerializable {

    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the assert() fails.
     */
    public function validate() {
        $props = get_object_vars( $this );

        v::key( 'amount', v::notEmpty()->intVal() )
         ->assert( $props );
    }

    /**
     * Total amount to refund this item, in currency's minor units.
     *
     * @var int
     */
    protected $amount;

    /**
     * Unique stamp of the refund item.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : int {
        return $this->amount;
    }

    /**
     * Set the total amount to refund this item, in currency's minor units.
     *
     * @param int $amount The amount.
     * @return RefundItem Return self to enable chaining.
     */
    public function setAmount(? int $amount ) : RefundItem {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp() : string {
        return $this->stamp;
    }

    /**
     * Set a unique stamp of the refund item.
     *
     * @param string $stamp The stamp.
     * @return RefundItem Return self to enable chaining.
     */
    public function setStamp( ?string $stamp ) : RefundItem {
        $this->stamp = $stamp;

        return $this;
    }

}
