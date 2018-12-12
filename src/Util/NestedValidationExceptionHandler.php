<?php
/**
 * Trait NestedValidationExceptionHandler
 */

namespace CheckoutFinland\SDK\Util;

use CheckoutFinland\SDK\Exception\PropertyException;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Trait NestedValidationExceptionHandler
 *
 * @package CheckoutFinland\SDK\Util
 */
trait NestedValidationExceptionHandler {

    /**
     * A method for handling nested exceptions from validations.
     *
     * @param NestedValidationException $e The exceptions.
     *
     * @throws PropertyException
     */
    protected function handle_nested_validation_exception( NestedValidationException $e ) {
        // Collect all errors
        $messages = array_map( function( $message ) {
            return $message;
        }, $e->getMessages() );

        // Throw a property exception with all the errors.
        throw new PropertyException( join( ', ' , $messages ) );
    }

}
