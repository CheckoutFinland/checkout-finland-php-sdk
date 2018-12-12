<?php
/**
 * Class Item
 */

namespace CheckoutFinland\SDK\Model;

/**
 * Class Item
 *
 * This class defines payment item details.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=item
 * @package CheckoutFinland\SDK\Model
 */
class Item {

    /**
     * Price per unit, VAT included, in each country's
     * minor unit, e.g. for Euros use cents.
     *
     * @var integer
     */
    protected $unitPrice;

    /**
     * Quantity, how many items ordered.
     *
     * @var integer
     */
    protected $units;

    /**
     * The VAT percentage.
     *
     * @var integer
     */
    protected $vatPercentage;

    /**
     * Merchant product code.
     * May appear on invoices of certain payment methods.
     *
     * @var string
     */
    protected $productCode;

    /**
     * The delivery date.
     *
     * @var string
     */
    protected $deliveryDate;

    /**
     * Item description.
     * May appear on invoices of certain payment methods.
     *
     * @var string
     */
    protected $description;

    /**
     * Merchant specific item category.
     *
     * @var string
     */
    protected $category;

    /**
     * Unique identifier for this item.
     * Required for Shop-in-Shop payments.
     *
     * @var string
     */
    protected $stamp;

    /**
     * Reference for this item.
     * Required for Shop-in-Shop payments.
     *
     * @var string
     */
    protected $reference;

    /**
     * Merchant ID for the item.
     * Required for Shop-in-Shop payments, do not use for normal payments.
     *
     * @var string
     */
    protected $merchant;

    /**
     * Shop-in-Shop commission.
     * Do not use for normal payments.
     *
     * @var Comission
     */
    protected $commission;

    /**
     * Get the unit price.
     *
     * @return int
     */
    public function getUnitPrice(): int {

        return $this->unitPrice;
    }

    /**
     * Set the unit price.
     *
     * @param int $unitPrice
     */
    public function setUnitPrice( int $unitPrice ): void {

        $this->unitPrice = $unitPrice;
    }

    /**
     * Get the units.
     *
     * @return int
     */
    public function getUnits(): int {

        return $this->units;
    }

    /**
     * Set the units.
     *
     * @param int $units
     */
    public function setUnits( int $units ): void {

        $this->units = $units;
    }

    /**
     * Get the VAT percentage.
     *
     * @return int
     */
    public function getVatPercentage(): int {

        return $this->vatPercentage;
    }

    /**
     * Set the VAT percentage.
     *
     * @param int $vatPercentage
     */
    public function setVatPercentage( int $vatPercentage ): void {

        $this->vatPercentage = $vatPercentage;
    }

    /**
     * Get the product code.
     *
     * @return string
     */
    public function getProductCode(): string {

        return $this->productCode;
    }

    /**
     * Set the product code.
     *
     * @param string $productCode
     */
    public function setProductCode( string $productCode ): void {

        $this->productCode = $productCode;
    }

    /**
     * Get the delivery date.
     *
     * @return string
     */
    public function getDeliveryDate(): string {

        return $this->deliveryDate;
    }

    /**
     * Set the delivery date.
     *
     * @param string $deliveryDate
     */
    public function setDeliveryDate( string $deliveryDate ): void {

        $this->deliveryDate = $deliveryDate;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription(): string {

        return $this->description;
    }

    /**
     * Set the description.
     *
     * @param string $description
     */
    public function setDescription( string $description ): void {

        $this->description = $description;
    }

    /**
     * Get the category.
     *
     * @return string
     */
    public function getCategory(): string {

        return $this->category;
    }

    /**
     * Set the category.
     *
     * @param string $category
     */
    public function setCategory( string $category ): void {

        $this->category = $category;
    }

    /**
     * Get the stamp.
     *
     * @return string
     */
    public function getStamp(): string {

        return $this->stamp;
    }

    /**
     * Set the stamp.
     *
     * @param string $stamp
     */
    public function setStamp( string $stamp ): void {

        $this->stamp = $stamp;
    }

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference(): string {

        return $this->reference;
    }

    /**
     * Set the reference.
     *
     * @param string $reference
     */
    public function setReference( string $reference ): void {

        $this->reference = $reference;
    }

    /**
     * Get the merchant.
     *
     * @return string
     */
    public function getMerchant(): string {

        return $this->merchant;
    }

    /**
     * Set the merchant.
     *
     * @param string $merchant
     */
    public function setMerchant( string $merchant ): void {

        $this->merchant = $merchant;
    }

    /**
     * Get the commission.
     *
     * @return Comission
     */
    public function getCommission(): Comission {

        return $this->commission;
    }

    /**
     * Set the commission.
     *
     * @param Comission $commission
     */
    public function setCommission( Comission $commission ): void {

        $this->commission = $commission;
    }
}