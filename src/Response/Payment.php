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
    public function getTransactionId(): string {

        return $this->transactionId;
    }

    /**
     * Set the transaction id.
     *
     * @param string $transactionId
     */
    public function setTransactionId( string $transactionId ): void {

        $this->transactionId = $transactionId;
    }

    /**
     * Get the href.
     *
     * @return string
     */
    public function getHref(): string {

        return $this->href;
    }

    /**
     * Set the href.
     *
     * @param string $href
     */
    public function setHref( string $href ): void {

        $this->href = $href;
    }

    /**
     * Get providers.
     *
     * @return Provider[]
     */
    public function getProviders(): array {

        return $this->providers;
    }

    /**
     * Set the providers.
     *
     * The parameter can be an arrya of Provider objects
     * or an array of stdClass instance. The latter will
     * be converted into provider class instances.
     *
     * @param Provider[]|array $providers
     */
    public function setProviders( ?array $providers ): void {
        if ( empty( $providers ) ) {
            return;
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
    }

}
