<?php
/**
 *
 */

namespace CheckoutFinland\SDK;

use CheckoutFinland\SDK\Util\JsonSerializable;
use CheckoutFinland\SDK\Util\PropertyBinder;

abstract class Response implements  \JsonSerializable {

    use PropertyBinder;
    use JsonSerializable;

}