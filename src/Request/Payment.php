<?php
/**
 *
 */

namespace CheckoutFinland\SDK\Request;

use CheckoutFinland\SDK\Model\Address;
use CheckoutFinland\SDK\Model\CallbackUrl;
use CheckoutFinland\SDK\Model\Customer;
use CheckoutFinland\SDK\Model\Item;

class Payment {

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
     */
    public function setStamp( string $stamp ): void {

        $this->stamp = $stamp;
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
     */
    public function setReference( string $reference ): void {

        $this->reference = $reference;
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
     */
    public function setAmount( int $amount ): void {

        $this->amount = $amount;
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
     */
    public function setCurrency( string $currency ): void {

        $this->currency = $currency;
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
     */
    public function setLanguage( string $language ): void {

        $this->language = $language;
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
     */
    public function setItems( array $items ): void {

        $this->items = $items;
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
     */
    public function setCustomer( Customer $customer ): void {

        $this->customer = $customer;
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
     */
    public function setDeliveryAddress( Address $deliveryAddress ): void {

        $this->deliveryAddress = $deliveryAddress;
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
     */
    public function setInvoicingAddress( array $invoicingAddress ): void {

        $this->invoicingAddress = $invoicingAddress;
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
     */
    public function setRedirectUrls( CallbackUrl $redirectUrls ): void {

        $this->redirectUrls = $redirectUrls;
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
     */
    public function setCallbackUrls( CallbackUrl $callbackUrls ): void {

        $this->callbackUrls = $callbackUrls;
    }

}
