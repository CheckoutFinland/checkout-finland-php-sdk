<?php
/**
 * Class Customer
 */

namespace OpMerchantServices\SDK\Model\Token;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Util\ObjectPropertyConverter;

/**
 * Class Customer
 *
 * The customer class defines the customer details object.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=customer
 * @package OpMerchantServices\SDK\Model\Token
 */
class Customer implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToSnake();

        if (empty($props['network_address'])) {
            throw new ValidationException('Network address is empty');
        }

        if (empty($props['country_code'])) {
            throw new ValidationException('Country code is empty');
        }

        return true;
    }

    /** @var string $networkAddress */
    protected $networkAddress;

    /** @var string $countryCode */
    protected $countryCode;

    /**
     * @param string $networkAddress
     * @return Customer
     */
    public function setNetworkAddress(string $networkAddress): Customer
    {
        $this->networkAddress = $networkAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    /**
     * @param string $countryCode
     * @return Customer
     */
    public function setCountryCode(string $countryCode): Customer
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter($this->convertObjectVarsToSnake(), function ($item) {
            return $item !== null;
        });
    }

    /**
     * @param \stdClass $customer
     * @return Customer
     */
    public function loadFromStdClass(\stdClass $customer): Customer
    {
        $this->setNetworkAddress($customer->network_address);
        $this->setCountryCode($customer->country_code);

        return $this;
    }
}
