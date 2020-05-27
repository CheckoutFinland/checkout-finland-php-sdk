<?php
/**
 * TODO: To be implemented.
 */

namespace OpMerchantServices\SDK\Model;

use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Util\JsonSerializable;

/**
 * Class Commission
 *
 * @package OpMerchantServices\SDK\Model
 */
class Commission implements \JsonSerializable
{

  use JsonSerializable;

  /**
   * Validates with Respect\Validation library and throws an exception for invalid objects
   *
   * @throws ValidationException
   */
  public function validate()
  {
    $props = get_object_vars($this);

    if (empty($props['amount'])) {
      throw new ValidationException('Amount is empty');
    }

    return true;
  }

  /**
   * Commission Merchant ID for the commission.
   * Required for Shop-in-Shop payments, do not use for normal payments.
   *
   * @var string
   */
  protected $merchant;

  /**
   * Total amount to commission this item, in currency's minor units.
   *
   * @var int
   */
  protected $amount;

  /**
   * Get the merchant.
   *
   * @return string
   */
  public function getMerchant(): ?string
  {
    return $this->merchant;
  }

  /**
   * Set the merchant.
   *
   * @param string $merchant
   * @return Commission Return self to enable chaining.
   */
  public function setMerchant(?string $merchant) : Commission
  {
    $this->merchant = $merchant;

    return $this;
  }

  /**
   * Get the amount.
   *
   * @return int
   */
  public function getAmount() : int
  {
    return $this->amount;
  }

  /**
   * Set the total amount to refund this item, in currency's minor units.
   *
   * @param int $amount The amount.
   * @return Commission Return self to enable chaining.
   */
  public function setAmount(? int $amount) : Commission
  {
    $this->amount = $amount;

    return $this;
  }

}
