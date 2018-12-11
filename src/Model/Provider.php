<?php
/**
 *
 */

namespace CheckoutFinland\SDK\Model;

use CheckoutFinland\SDK\Util\PropertyBinder;

class Provider {

    use PropertyBinder;

    /**
     * The provider redirect url.
     *
     * @var string
     */
    protected $url;

    /**
     * The provider icon url.
     *
     * @var string
     */
    protected $icon;

    /**
     * The provider svg url.
     *
     * @var string
     */
    protected $svg;

    /**
     * The provider name.
     *
     * @var string
     */
    protected $name;

    /**
     * The provider group.
     *
     * @var string
     */
    protected $group;

    /**
     * The provider id.
     *
     * @var string
     */
    protected $id;

    /**
     * The provider parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * The getter for the url.
     *
     * @return string
     */
    public function getUrl(): string {

        return $this->url;
    }

    /**
     * The setter for the url.
     *
     * @param string $url
     */
    public function setUrl( string $url ): void {

        $this->url = $url;
    }

    /**
     * The getter for the icon.
     *
     * @return string
     */
    public function getIcon(): string {

        return $this->icon;
    }

    /**
     * The setter for the icon.
     *
     * @param string $icon
     */
    public function setIcon( string $icon ): void {

        $this->icon = $icon;
    }

    /**
     * The getter for the svg.
     *
     * @return string
     */
    public function getSvg(): string {

        return $this->svg;
    }

    /**
     * The setter for the svg.
     *
     * @param string $svg
     */
    public function setSvg( string $svg ): void {

        $this->svg = $svg;
    }

    /**
     * The getter for the name.
     *
     * @return string
     */
    public function getName(): string {

        return $this->name;
    }

    /**
     * The setter for the name.
     *
     * @param string $name
     */
    public function setName( string $name ): void {

        $this->name = $name;
    }

    /**
     * The getter for the group.
     *
     * @return string
     */
    public function getGroup(): string {

        return $this->group;
    }

    /**
     * The setter for the group.
     *
     * @param string $group
     */
    public function setGroup( string $group ): void {

        $this->group = $group;
    }

    /**
     * The getter for the id.
     *
     * @return string
     */
    public function getId(): string {

        return $this->id;
    }

    /**
     * The setter for the id.
     *
     * @param string $id
     */
    public function setId( string $id ): void {

        $this->id = $id;
    }

    /**
     * The getter for the parameters.
     *
     * @return array
     */
    public function getParameters(): array {

        return $this->parameters;
    }

    /**
     * The setter for the parameters.
     *
     * @param array $parameters
     */
    public function setParameters( array $parameters ): void {

        $this->parameters = $parameters;
    }
}