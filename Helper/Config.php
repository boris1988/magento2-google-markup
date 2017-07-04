<?php

namespace Borisperevyazko\GoogleMarkup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @author Boris Pereviazko <borisperevyazko@gmail.com>
 */
class Config extends AbstractHelper
{
    /**#@+
     * Define xml path for system.xml
     *
     * @var string
     */
    const XML_PATH_IS_ENABLED       = 'borisperevyazko_googlemarkup/general/enable';
    const XML_PATH_SHOW_OFFER       = 'borisperevyazko_googlemarkup/product/show_offer';
    const XML_PATH_SHOW_RATING      = 'borisperevyazko_googlemarkup/product/show_rating';
    const XML_PATH_SHOW_SEARCHBOX   = 'borisperevyazko_googlemarkup/website/enable_searchbox';
    const XML_PATH_WEBSITE_ALT_TEXT = 'borisperevyazko_googlemarkup/website/alt_text';
    /**#@-*/

    /**
     * Check if google markup enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_IS_ENABLED, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Check if show Offer property
     *
     * @return bool
     */
    public function isShowOffer()
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_SHOW_OFFER, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Check if show aggregateRating property
     *
     * @return bool
     */
    public function isShowAggregateRating()
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_SHOW_RATING, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Check if searchbox enable
     *
     * @return bool
     */
    public function isSearchboxEnable()
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_SHOW_SEARCHBOX, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get alternate text
     *
     * @return string
     */
    public function getWebsiteAltText()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_WEBSITE_ALT_TEXT, ScopeInterface::SCOPE_WEBSITE);
    }
}