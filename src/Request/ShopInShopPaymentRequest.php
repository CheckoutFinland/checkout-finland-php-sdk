<?php
/**
 * Class PaymentRequest
 */

namespace OpMerchantServices\SDK\Request;

use OpMerchantServices\SDK\Model\Item;

/**
 * Class PaymentRequest
 *
 * This class is used to create a payment request object for
 * the CheckoutFinland\SDK\Client class.
 *
 * @see https://checkoutfinland.github.io/psp-api/#/?id=create-request-body
 * @package OpMerchantServices\SDK\Request
 */
class ShopInShopPaymentRequest extends PaymentRequest
{
    /**
     * Validates with Respect\Validation library and throws an exception for invalid objects
     *
     * @throws ValidationException
     */
    public function validate()
    {
        parent::validate();

        // Validate the shop-in-shop items.
        array_walk($this->items, function (Item $item) {
            $item->validateShopInShop();
        });

        return true;
    }
}
