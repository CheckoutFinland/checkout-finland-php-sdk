<?php
/**
 * Class Customer
 */

namespace CheckoutFinland\SDK\Model;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use CheckoutFinland\SDK\Util\JsonSerializable;

/**
 * Class Customer
 *
 * The customer class defines the customer details object.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=customer
 * @package CheckoutFinland\SDK\Model
 */
class Customer implements \JsonSerializable {

    use JsonSerializable;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws NestedValidationException Thrown when the assert() fails.
     */
    public function validate() {
        $props = get_object_vars( $this );

        v::key( 'email', v::notEmpty()->email() )
        ->assert( $props );
    }

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
    public function getEmail(): ?string {

        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return self Return self to enable chaining.
     */
    public function setEmail( ?string $email ) : Customer {
        $this->email = $email;

        return $this;
    }

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): ?string {

        return $this->firstName;
    }

    /**
     * Set first name.
     *
     * @param string $firstName
     *
     * @return self Return self to enable chaining.
     */
    public function setFirstName( ?string $firstName ) : Customer {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName(): ?string {

        return $this->lastName;
    }

    /**
     * Set last name.
     *
     * @param string $lastName
     *
     * @return self Return self to enable chaining.
     */
    public function setLastName( ?string $lastName ) : Customer {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): ?string {

        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return self Return self to enable chaining.
     */
    public function setPhone( ?string $phone ) : Customer {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get VAT id.
     *
     * @return string
     */
    public function getVatId(): ?string {

        return $this->vatId;
    }

    /**
     * Set VAT id.
     *
     * @param string $vatId
     *
     * @return self Return self to enable chaining.
     */
    public function setVatId( ?string $vatId ) : Customer {
        $this->vatId = $vatId;

        return $this;
    }

}
