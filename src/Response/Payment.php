<?php
/**
 *
 */

namespace CheckoutFinland\SDK\Response;

use CheckoutFinland\SDK\Model\Provider;
use CheckoutFinland\SDK\Response;

class Payment extends Response {

    /**
     * The transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Payment API url.
     *
     * @var string
     */
    protected $href;

    /**
     * Payment providers.
     *
     * @var Provider[]
     */
    protected $providers = [];

    /**
     * Get the transaction id.
     *
     * @return string
     */
    public function getTransactionId() : string {
        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     * @return Payment Return self to enable chaining.
     */
    public function setTransactionId( string $transactionId ) : Payment {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get the href.
     *
     * @return string
     */
    public function getHref() : string {
        return $this->href;
    }

    /**
     * Set the href.
     *
     * @param string $href
     * @return Payment Return self to enable chaining.
     */
    public function setHref( string $href ) : Payment {
        $this->href = $href;

        return $this;
    }

    /**
     * Get providers.
     *
     * @return Provider[]
     */
    public function getProviders() : array {

        return $this->providers;
    }

    /**
     * Set the providers.
     *
     * The parameter can be an arrya of Provider objects
     * or an array of stdClass instance. The latter will
     * be converted into provider class instances.
     *
     * @param Provider[]|array $providers The providers.
     * @return Payment Return self to enable chaining.
     */
    public function setProviders( ?array $providers ) : Payment {
        if ( empty( $providers ) ) {
            return $this;
        }

        array_walk( $providers, function( $provider ) {
            if ( ! $provider instanceof  Provider ) {
                $instance = new Provider();
                $instance->bind_properties( $provider );
                $this->providers[] = $instance;
            }
            else {
                $this->providers[] = $provider;
            }
        } );

        return $this;
    }
}
