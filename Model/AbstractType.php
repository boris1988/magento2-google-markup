<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;
use Borisperevyazko\GoogleMarkup\Helper\Config;

/**
 * Class AbstractType
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
abstract class AbstractType implements JsonLdTypeInterface
{

    const DEFINE_CONTEXT_TYPE = "http://schema.org/";
    const DEFINE_CONTEXT_KEY = '@context';
    const DEFINE_TYPE_KEY = '@type';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * AbstractType constructor
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->addContext();
        $this->configHelper = $config;
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