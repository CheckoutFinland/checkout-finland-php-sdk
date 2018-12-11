<?php
/**
 *
 */

namespace CheckoutFinland\SDK\Request;

use CheckoutFinland\SDK\Exception\MissingParameter;
use CheckoutFinland\SDK\Exception;
use CheckoutFinland\SDK\Model\Address;
use CheckoutFinland\SDK\Model\CallbackUrl;
use CheckoutFinland\SDK\Model\Customer;
use CheckoutFinland\SDK\Model\Item;
use CheckoutFinland\SDK\Util\JsonSerializable;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Payment implements \JsonSerializable {

    use JsonSerializable;

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
     * @var Item[]
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
     * Validates with Respect\Validation library and throws exception for invalid objects
     *
     * @throws Exception\Property
     */
    public function validate() {
        $props = get_object_vars( $this );

        try {
            v::key( 'amount', v::notEmpty()->intVal() )
                ->key( 'reference', v::notEmpty()->alnum()->length( 1, 50 ) )
                ->key( 'stamp', v::notEmpty()->alnum()->length( 1, 20 ) )
                ->key( 'currency', v::notEmpty()->equals( 'EUR' ) )
                ->key( 'language', v::oneOf(
                    v::equals( 'FI' ),
                    v::equals( 'SV' ),
                    v::equals( 'EN' )
                ) )
                ->key( 'items', v::notEmpty( ) )
                ->key( 'customer', v::notEmpty( ) )
                ->key( 'redirectUrls', v::notEmpty( ) )
                ->assert( $props );
        }
        catch ( NestedValidationException $e ) {
            // Collect all errors
            $messages = array_map( function( $message ) {
                return $message;
            }, $e->getMessages() );

            // Throw a property exception with all the errors.
            throw new Exception\Property( join( ', ' , $messages ) );
        }
    }

    /**
     * Get stamp.
     *
     * @return string
     */
    public function getStamp(): string {

        return $this->stamp;
    }

    /**

    Set     .
     * @param string $stamp
     * @return self Return the instance to enable chaining.
     */
    public function setStamp( string $stamp ) : Payment {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference(): string {

        return $this->reference;
    }

    /**
     * Set reference.
     *
     * @param string $reference
     * @return self Return the instance to enable chaining.
     */
    public function setReference( string $reference ) : Payment {

        $this->reference = $reference;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int
     */
    public function getAmount(): int {

        return $this->amount;
    }

    /**

    Set
    .
     * @param int $amount
     * @return self Return the instance to enable chaining.
     */
    public function setAmount( int $amount ) : Payment {

        $this->amount = $amount;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency(): string {

        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     * @return self Return the instance to enable chaining.
     */
    public function setCurrency( string $currency ) : Payment {

        $this->currency = $currency;

        return $this;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage(): string {

        return $this->language;
    }

    /**
     * Set language.
     *
     * @param string $language
     * @return self Return the instance to enable chaining.
     */
    public function setLanguage( string $language ) : Payment {

        $this->language = $language;

        return $this;
    }

    /**
     * Get items.
     *
     * @return Item[]
     */
    public function getItems(): array {

        return $this->items;
    }

    /**

    Set     .
     * @param Item[] $items
     * @return self Return the instance to enable chaining.
     */
    public function setItems( array $items ) : Payment {

        $this->items = $items;

        return $this;
    }

    /**
     * Get customer.
     *
     * @return Customer
     */
    public function getCustomer(): Customer {

        return $this->customer;
    }

    /**
     * Set customer.
     *
     * @param Customer $customer
     * @return self Return the instance to enable chaining.
     */
    public function setCustomer( Customer $customer ) : Payment {

        $this->customer = $customer;

        return $this;
    }

    /**
     * Get deliveryAddress.
     *
     * @return Address
     */
    public function getDeliveryAddress(): Address {

        return $this->deliveryAddress;
    }

    /**
     * Set deliveryAddress.
     *
     * @param Address $deliveryAddress
     * @return self Return the instance to enable chaining.
     */
    public function setDeliveryAddress( Address $deliveryAddress ) : Payment {

        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get invoicingAddress.
     *
     * @return Item[]
     */
    public function getInvoicingAddress(): array {

        return $this->invoicingAddress;
    }

    /**
     * Set invoicingAddress.
     *
     * @param Item[] $invoicingAddress
     * @return self Return the instance to enable chaining.
     */
    public function setInvoicingAddress( array $invoicingAddress ) : Payment {

        $this->invoicingAddress = $invoicingAddress;

        return $this;
    }

    /**
     * Get redirectUrls.
     *
     * @return CallbackUrl
     */
    public function getRedirectUrls(): CallbackUrl {

        return $this->redirectUrls;
    }

    /**
     * Set redirectUrls.
     *
     * @param CallbackUrl $redirectUrls
     * @return self Return the instance to enable chaining.
     */
    public function setRedirectUrls( CallbackUrl $redirectUrls ) : Payment {

        $this->redirectUrls = $redirectUrls;

        return $this;
    }

    /**
     * Get callbackUrls.
     *
     * @return CallbackUrl
     */
    public function getCallbackUrls(): CallbackUrl {

        return $this->callbackUrls;
    }

    /**
     * Set callbackUrls.
     *
     * @param CallbackUrl $callbackUrls
     * @return self Return the instance to enable chaining.
     */
    public function setCallbackUrls( CallbackUrl $callbackUrls ) : Payment {

        $this->callbackUrls = $callbackUrls;

        return $this;
    }

}
