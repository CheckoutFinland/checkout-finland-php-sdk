<?php
/**
 * Class Customer
 */

namespace CheckoutFinland\SDK\Model;

/**
 * Class Customer
 *
 * The customer class defines the customer details object.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=customer
 * @package CheckoutFinland\SDK\Model
 */
class Customer {

    /**
     * The customer email.
     *
     * @var string
     */
    protected $email;

    /**
     * The customer first name.
     *
     * @var string
     */
    protected $firstName;

    /**
     * The customer last name.
     *
     * @var string
     */
    protected $lastName;

    /**
     * The customer phone.
     *
     * @var string
     */
    protected $phone;

    /**
     * The customer VAT id.
     *
     * @var string
     */
    protected $vatId;

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string {

        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     */
    public function setEmail( string $email ): void {

        $this->email = $email;
    }

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): string {

        return $this->firstName;
    }

    /**
     * Set first name.
     *
     * @param string $firstName
     */
    public function setFirstName( string $firstName ): void {

        $this->firstName = $firstName;
    }

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName(): string {

        return $this->lastName;
    }

    /**
     * Set last name.
     *
     * @param string $lastName
     */
    public function setLastName( string $lastName ): void {

        $this->lastName = $lastName;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): string {

        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     */
    public function setPhone( string $phone ): void {

        $this->phone = $phone;
    }

    /**
     * Get VAT id.
     *
     * @return string
     */
    public function getVatId(): string {

        return $this->vatId;
    }

    /**
     * Set VAT id.
     *
     * @param string $vatId
     */
    public function setVatId( string $vatId ): void {

        $this->vatId = $vatId;
    }

}
