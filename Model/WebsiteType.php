<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Helper\Config;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class WebsiteType
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
class WebsiteType extends AbstractType
{

    /**#@+
     * Define properties constants
     *
     * @var string
     */
    const DEFINE_TYPE                         = 'WebSite';
    const DEFINE_WEBSITE_URL_KEY              = 'url';
    const DEFINE_WEBSITE_POTENTIAL_ACTION_KEY = 'potentialAction';
    const DEFINE_WEBSITE_TARGET_KEY           = 'target';
    const DEFINE_WEBSITE_QUERY_INPUT_KEY      = 'query-input';
    const DEFINE_WEBSITE_QUERY_INPUT_VALUE    = 'required name=search_term_string';
    const DEFINE_WEBSITE_POTENTIAL_TYPE       = 'SearchAction';
    const DEFINE_WEBSITE_NAME_KEY             = 'name';
    const DEFINE_WEBSITE_ALTNAME_KEY          = 'alternateName';
    /**#@-*/

    /**#@+
     * Define search key-value constant
     *
     * @var string
     */
    const DEFINE_SEARCH_CONST = "{search_term_string}";
    const DEFINE_SEARCH_KEY   = 'q';
    /**#@-*/

    /**
     * @var StoreManagerInterface
     */
    protected $store;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * WebsiteType constructor
     *
     * @param Config $config
     * @param StoreManagerInterface $storeManagementInterface
     * @param UrlInterface $urlInterface
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManagementInterface,
        UrlInterface $urlInterface
    ) {
        $this->addType();
        parent::__construct($config);
        $this->store = $storeManagementInterface;
        $this->url = $urlInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function addType()
    {
        $this->type = static::DEFINE_TYPE;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function initProperties()
    {
        $this->addProperty(AbstractType::DEFINE_CONTEXT_KEY, $this->getContext())
            ->addProperty(AbstractType::DEFINE_TYPE_KEY, $this->getType())
            ->addProperty(static::DEFINE_WEBSITE_URL_KEY, $this->store->getStore()->getBaseUrl());

        if ($this->configHelper->isSearchboxEnable()) {
            $this->addProperty(static::DEFINE_WEBSITE_POTENTIAL_ACTION_KEY, $this->addPotentialActionProperty());
        }

        $this->addProperty(static::DEFINE_WEBSITE_NAME_KEY, $this->store->getStore()->getName());
        if ($this->configHelper->getWebsiteAltText()) {
            $this->addProperty(static::DEFINE_WEBSITE_ALTNAME_KEY, $this->configHelper->getWebsiteAltText());
        }

        return $this;
    }

    /**
     * Create PotentialAction property
     *
     * @return array
     */
    protected function addPotentialActionProperty()
    {
        $propertyArr = [static::DEFINE_TYPE_KEY => static::DEFINE_WEBSITE_POTENTIAL_TYPE];
        $propertyArr[ static::DEFINE_WEBSITE_TARGET_KEY ] = $this->url->getUrl('catalogsearch/result')
            . '?' . static::DEFINE_SEARCH_KEY . '=' . static::DEFINE_SEARCH_CONST;
        $propertyArr[ static::DEFINE_WEBSITE_QUERY_INPUT_KEY ] = static::DEFINE_WEBSITE_QUERY_INPUT_VALUE;

        return $propertyArr;
    }


}