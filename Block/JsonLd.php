<?php

namespace Borisperevyazko\GoogleMarkup\Block;

use Borisperevyazko\GoogleMarkup\Model\ProductType;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class JsonLd extends Template
{
    protected $jsonLd;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    public function __construct(
        HttpRequest $request,
        Template\Context $context,
        Registry $registry,
        JsonHelper $jsonHelper,
        array $data
    ) {
        $this->request = $request;
        $this->registry = $registry;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    public function getAjaxUrl()
    {
        return $this->_urlBuilder->getUrl("googlemarkup/ajax/jsonld",
            ['fullActionName' => $this->request->getFullActionName()]
        );
    }

    public function getJsonLd()
    {
        return $this->jsonLd;
    }

    public function getProduct()
    {
       return $this->registry->registry('current_product');
    }

    public function getPostData()
    {
       return $this->jsonHelper->jsonEncode(
           [
               'fullActionName' => $this->request->getFullActionName(),
                'id' => $this->getProduct()->getId()
           ]
       );
    }

}