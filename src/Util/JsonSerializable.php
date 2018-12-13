<?php
/**
 * Trait JsonSerializable
 */

namespace CheckoutFinland\SDK\Util;

/**
 * Trait JsonSerializable
 */
trait JsonSerializable {

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     *
     * @return \stdClass
     */
    public function jsonSerialize() {
        return get_object_vars( $this );
    }

}
