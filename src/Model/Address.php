<?php
/**
 * Class Address
 */

namespace CheckoutFinland\SDK\Model;

/**
 * Class Address
 *
 * This class defines address details for a payment request.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=address
 * @package CheckoutFinland\SDK\Model
 */
class Address {

    /**
     * The street address.
     *
     * @var string
     */
    protected $streetAddress;

    /**
     * The postal code.
     *
     * @var string
     */
    protected $postalCode;

    /**
     * The city.
     *
     * @var string
     */
    protected $city;

    /**
     * The county.
     *
     * @var string
     */
    protected $county;

    /**
     * The country.
     *
     * @var string
     */
    protected $country;

    /**
     * Get the street address.
     *
     * @return string
     */
    public function getStreetAddress(): string {

        return $this->streetAddress;
    }

    /**
     * Set the sttreet address.
     *
     * @param string $streetAddress
     */
    public function setStreetAddress( string $streetAddress ): void {

        $this->streetAddress = $streetAddress;
    }

    /**
     * Get the postal code.
     *
     * @return string
     */
    public function getPostalCode(): string {

        return $this->postalCode;
    }

    /**
     * Set the tostal code.
     *
     * @param string $postalCode
     */
    public function setPostalCode( string $postalCode ): void {

        $this->postalCode = $postalCode;
    }

    /**
     * Get the city.
     *
     * @return string
     */
    public function getCity(): string {

        return $this->city;
    }

    /**
     * Set the city.
     *
     * @param string $city
     */
    public function setCity( string $city ): void {

        $this->city = $city;
    }

    /**
     * Get the county.
     *
     * @return string
     */
    public function getCounty(): string {

        return $this->county;
    }

    /**
     * Set the county.
     *
     * @param string $county
     */
    public function setCounty( string $county ): void {

        $this->county = $county;
    }

    /**
     * Get the country.
     *
     * @return string
     */
    public function getCountry(): string {

        return $this->country;
    }

    /**
     * Set the country.
     *
     * @param string $country
     */
    public function setCountry( string $country ): void {

        $this->country = $country;
    }

}
