<?php
/**
 * Class PaymentRequest
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Interfaces\RequestInterface;
use OpMerchantServices\SDK\Model\Address;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Util\JsonSerializable;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Class PaymentRequest
 *
 * This class is used to create a payment request object for
 * the CheckoutFinland\SDK\Client class.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=create-request-body
 * @package OpMerchantServices\SDK\Request
 */
class PaymentRequest implements \JsonSerializable, RequestInterface
{

    use JsonSerializable;

    /**
     * @var array
     */
    private $supportedCurrencies = ['EUR'];

    /**
     * @var array
     */
    private $supportedLanguages = ['FI', 'SV', 'EN'];

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the assert() fails.
     * @throws ValidationException
     */
    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['items'])) {
            throw new ValidationException('Items is empty');
        }

        if (!is_array($props['items'])) {
            throw new ValidationException('Items needs to be type of array');
        }

        // Count the total amount of the payment.
        $items_total = array_reduce($this->getItems(), function ($carry = 0, ?Item $item = null) {
            if ($item === null) {
                return $carry;
            }
            return $item->getUnitPrice() * $item->getUnits() + $carry;
        });

        if (empty($props['amount'])) {
            throw new ValidationException('Amount is empty');
        }

        if (!filter_var($props['amount'], FILTER_VALIDATE_INT)) {
            throw new ValidationException('Amount is not a number');
        }

        if ($props['amount'] !== $items_total) {
            throw new ValidationException('Amount doesnt match ItemsTotal');
        }

        if (empty($props['stamp'])) {
            throw new ValidationException('Stamp is empty');
        }

        if (empty($props['reference'])) {
            throw new ValidationException('Reference is empty');
        }

        if (empty($props['currency'])) {
            throw new ValidationException('Currency is empty');
        }

        if (!in_array($props['currency'], $this->supportedCurrencies)) {
            throw new ValidationException('Unsupported currency chosen');
        }

        if (!in_array($props['language'], $this->supportedLanguages)) {
            throw new ValidationException('Unsupported language chosen');
        }

        if (empty($props['customer'])) {
            throw new ValidationException('Customer is empty');
        }

        if (empty($props['redirectUrls'])) {
            throw new ValidationException('RedirectUrls is empty');
        }

        // Validate the items.
        array_walk($this->items, function (Item $item) {
            $item->validate();
        });

        // Validate the customer.
        $this->customer->validate();

        // Validate the address values.
        if (! empty($this->deliveryAddress)) {
            $this->deliveryAddress->validate();
        }
        if (! empty($this->invoicingAddress)) {
            $this->invoicingAddress->validate();
        }

        return true;
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
    public function getStamp() : ?string
    {

        return $this->stamp;
    }

    /**
     * Set the stamp.
     *
     * @param string $stamp
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setStamp(?string $stamp): PaymentRequest
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference() : ?string
    {

        return $this->reference;
    }

    /**
     * Set the reference.
     *
     * @param string $reference
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setReference(?string $reference): PaymentRequest
    {

        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount() : ?int
    {

        return $this->amount;
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setAmount(?int $amount) : PaymentRequest
    {

        $this->amount = $amount;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency() : ?string
    {

        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setCurrency(?string $currency) : PaymentRequest
    {

        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the language.
     *
     * @return string
     */
    public function getLanguage() : ?string
    {

        return $this->language;
    }

    /**
     * Set the language.
     *
     * @param string $language
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setLanguage(?string $language) : PaymentRequest
    {

        $this->language = $language;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return Item[]
     */
    public function getItems() : ?array
    {

        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param Item[] $items
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setItems(?array $items) : PaymentRequest
    {

        $this->items = array_values($items);

        return $this;
    }

    /**
     * Get the customer.
     *
     * @return Customer
     */
    public function getCustomer() : ?Customer
    {

        return $this->customer;
    }

    /**
     * Set the customer.
     *
     * @param Customer $customer
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setCustomer(?Customer $customer) : PaymentRequest
    {

        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the delivery address.
     *
     * @return Address
     */
    public function getDeliveryAddress() : ?Address
    {

        return $this->deliveryAddress;
    }

    /**
     * Set the delivery address.
     *
     * @param Address $deliveryAddress
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setDeliveryAddress(?Address $deliveryAddress) : PaymentRequest
    {

        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get the invoicing address.
     *
     * @return Address
     */
    public function getInvoicingAddress() : ?Address
    {

        return $this->invoicingAddress;
    }

    /**
     * Set the invoicing address.
     *
     * @param Address $invoicingAddress
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setInvoicingAddress(?Address $invoicingAddress) : PaymentRequest
    {

        $this->invoicingAddress = $invoicingAddress;

        return $this;
    }

    /**
     * Get the redirect urls.
     *
     * @return CallbackUrl
     */
    public function getRedirectUrls() : ?CallbackUrl
    {

        return $this->redirectUrls;
    }

    /**
     * Set the redirect urls.
     *
     * @param CallbackUrl $redirectUrls
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setRedirectUrls(?CallbackUrl $redirectUrls) : PaymentRequest
    {

        $this->redirectUrls = $redirectUrls;

        return $this;
    }

    /**
     * Get callback urls.
     *
     * @return CallbackUrl
     */
    public function getCallbackUrls() : ?CallbackUrl
    {

        return $this->callbackUrls;
    }

    /**
     * Set callback urls.
     *
     * @param CallbackUrl $callbackUrls
     *
     * @return PaymentRequest Return self to enable chaining.
     */
    public function setCallbackUrls(?CallbackUrl $callbackUrls) : PaymentRequest
    {

        $this->callbackUrls = $callbackUrls;

        return $this;
    }
}
