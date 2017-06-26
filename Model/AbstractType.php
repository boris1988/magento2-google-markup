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
        $this->properties[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function addContext()
    {
        $this->context = static::DEFINE_CONTEXT_TYPE;
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function getType()
    {
        return $this->type;
    }

}