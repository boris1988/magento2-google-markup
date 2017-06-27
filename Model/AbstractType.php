<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;

abstract class AbstractType implements JsonLdTypeInterface
{
    const DEFINE_CONTEXT_TYPE = "http://schema.org/";

    protected $type;

    protected $context;

    protected $properties;

    public function __construct()
    {
        $this->addContext();
    }

    /**
     * {@inheritdoc}
     */
    public function addProperty($key, $value)
    {
        $this->properties[ $key ] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addContext()
    {
        $this->context = static::DEFINE_CONTEXT_TYPE;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
       return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    abstract function initProperties();

}