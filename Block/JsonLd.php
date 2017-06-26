<?php

namespace Borisperevyazko\GoogleMarkup\Block;

use Borisperevyazko\GoogleMarkup\Model\ProductType;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Request\Http as HttpRequest;

class JsonLd extends Template
{
    protected $jsonLd;

    /**
     * @var HttpRequest
     */
    protected $request;

    public function __construct(
        HttpRequest $request,
        Template\Context $context,
        array $data
    ) {
        $this->request = $request;
        parent::__construct($context, $data);
    }

    public function getAjaxUrl()
    {
        return $this->_urlBuilder->getUrl("googlemarkup/ajax/jsonld");
    }

    protected function _beforeToHtml()
    {
        switch ($this->request->getFullActionName()) {
            case 'catalog_product_view':
                $this->jsonLd = new ProductType();
                break;
        }

        return parent::_beforeToHtml();
    }

    public function getJsonLd()
    {
        return $this->jsonLd;
    }
}