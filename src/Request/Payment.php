<?php
/**
 * Class Payment
 */

namespace CheckoutFinland\SDK\Request;

use CheckoutFinland\SDK\Exception\PropertyException;
use CheckoutFinland\SDK\Model\Address;
use CheckoutFinland\SDK\Model\CallbackUrl;
use CheckoutFinland\SDK\Model\Customer;
use CheckoutFinland\SDK\Model\Item;
use CheckoutFinland\SDK\Util\JsonSerializable;
use CheckoutFinland\SDK\Util\NestedValidationExceptionHandler;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class Payment
 *
 * This class is used to create a payment request object for
 * the CheckoutFinland\SDK\Client class.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=create-request-body
 * @package CheckoutFinland\SDK\Request
 */
class Payment implements \JsonSerializable {

    use JsonSerializable;
    use NestedValidationExceptionHandler;

    /**
     * Validates with Respect\Validation library and throws exception for invalid objects
     *
     * @throws PropertyException
     */
    public function validate() {
        $props = get_object_vars( $this );

        try {
            v::key( 'stamp', v::notEmpty() )
             ->key( 'reference', v::notEmpty() )
             ->key( 'amount', v::notEmpty()->intVal() )
             ->key( 'currency', v::notEmpty()->equals( 'EUR' ) )
             ->key( 'language', v::oneOf(
                 v::equals( 'FI' ),
                 v::equals( 'SV' ),
                 v::equals( 'EN' )
             ) )
             ->key( 'items', v::notEmpty()->arrayType() )
             ->key( 'customer', v::notEmpty() )
             ->key( 'redirectUrls', v::notEmpty() )
             ->assert( $props );

            // Validate the items.
            array_walk( $this->items, function( Item $item ) {
                $item->validate();
            } );

            // Validate the customer.
            $this->customer->validate();

            // Validate the address values.
            if ( ! empty( $this->deliveryAddress ) ) {
                $this->deliveryAddress->validate();
            }
            if ( ! empty( $this->invoicingAddress ) ) {
                $this->invoicingAddress->validate();
            }
        }
        catch ( NestedValidationException $e ) {
            $this->handle_nested_validation_exception( $e );
        }
    }

    /**
     * Merchant unique identifier for the order.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Order reference.
     *
     * @var string
     */
    protected $reference;

    /**
     * Total amount of the payment in currency's minor units,
     * eg. for Euros use cents. Must match the total sum of items.
     *
     * @var integer
     */
    protected $amount;

    /**
     * Currency, only EUR supported at the moment.
     *
     * @var string
     */
    protected $currency;

    /**
     * Payment's language, currently supported are FI, SV, and EN
     *
     * @var string
     */
    protected $language;

    /**
     * Array of items.
     *
     * @var Item[]
     */
    protected $items;

    /**
     * Customer information.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Delivery address.
     *
     * @var Address
     */
    protected $deliveryAddress;

    /**
     * Invoicing address.
     *
     * @var Address
     */
    protected $invoicingAddress;

    /**
     * Where to redirect browser after a payment is paid or cancelled.
     *
     * @var CallbackUrl;
     */
    protected $redirectUrls;

    /**
     * Which url to ping after this payment is paid or cancelled.
     *
     * @var CallbackUrl;
     */
    protected $callbackUrls;

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp() : ?string {

        return $this->stamp;
    }

    /**
     * Set the stamp.
     *
     * @param string $stamp
     * @return Payment Return self to enable chaining.
     */
    public function setStamp( ?string $stamp ) : Payment {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference() : ?string {

        return $this->reference;
    }

    /**
     * Set the reference.
     *
     * @param string $reference
     * @return Payment Return self to enable chaining.
     */
    public function setReference( ?string $reference ) : Payment {

        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : ?int {

        return $this->amount;
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     * @return Payment Return self to enable chaining.
     */
    public function setAmount( ?int $amount ) : Payment {

        $this->amount = $amount;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency() : ?string {

        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     * @return Payment Return self to enable chaining.
     */
    public function setCurrency( ?string $currency ) : Payment {

        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the language.
     *
     * @return string
     */
    public function getLanguage() : ?string {

        return $this->language;
    }

    /**
     * Set the language.
     *
     * @param string $language
     * @return Payment Return self to enable chaining.
     */
    public function setLanguage( ?string $language ) : Payment {

        $this->language = $language;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return Item[]
     */
    public function getItems() : ?array {

        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param Item[] $items
     * @return Payment Return self to enable chaining.
     */
    public function setItems( ?array $items ) : Payment {

        $this->items = $items;

        return $this;
    }

    /**
     * Get the customer.
     *
     * @return Customer
     */
    public function getCustomer() : ?Customer {

        return $this->customer;
    }

    /**
     * Set the customer.
     *
     * @param Customer $customer
     * @return Payment Return self to enable chaining.
     */
    public function setCustomer( ?Customer $customer ) : Payment {

        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the delivery address.
     *
     * @return Address
     */
    public function getDeliveryAddress() : ?Address {

        return $this->deliveryAddress;
    }

    /**
     * Set the delivery address.
     *
     * @param Address $deliveryAddress
     * @return Payment Return self to enable chaining.
     */
    public function setDeliveryAddress( ?Address $deliveryAddress ) : Payment {

        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get the invoicing address.
     *
     * @return Address
     */
    public function getInvoicingAddress() : ?Address {

        return $this->invoicingAddress;
    }

    /**
     * Set the invoicing address.
     *
     * @param Address $invoicingAddress
     * @return Payment Return self to enable chaining.
     */
    public function setInvoicingAddress( ?Address $invoicingAddress ) : Payment {

        $this->invoicingAddress = $invoicingAddress;

        return $this;
    }

    /**
     * Get the redirect urls.
     *
     * @return CallbackUrl
     */
    public function getRedirectUrls() : ?CallbackUrl {

        return $this->redirectUrls;
    }

    /**
     * Set the redirect urls.
     *
     * @param CallbackUrl $redirectUrls
     * @return Payment Return self to enable chaining.
     */
    public function setRedirectUrls( ?CallbackUrl $redirectUrls ) : Payment {

        $this->redirectUrls = $redirectUrls;

        return $this;
    }

    /**
     * Get callback urls.
     *
     * @return CallbackUrl
     */
    public function getCallbackUrls() : ?CallbackUrl {

        return $this->callbackUrls;
    }

    /**
     * Set callback urls.
     *
     * @param CallbackUrl $callbackUrls
     * @return Payment Return self to enable chaining.
     */
    public function setCallbackUrls( ?CallbackUrl $callbackUrls ) : Payment {

        $this->callbackUrls = $callbackUrls;

        return $this;
    }

}
