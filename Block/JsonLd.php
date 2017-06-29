<?php

namespace Borisperevyazko\GoogleMarkup\Block;

use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class JsonLd
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
class JsonLd extends Template
{
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

    /**
     * JsonLd constructor
     *
     * @param HttpRequest $request
     * @param Template\Context $context
     * @param Registry $registry
     * @param JsonHelper $jsonHelper
     * @param array $data
     */
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

    /**
     * Get url
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->_urlBuilder->getUrl("googlemarkup/ajax/jsonld");
    }

    /**
     * Return product
     *
     * @return CatalogProduct
     */
    public function getProduct()
    {
       return $this->registry->registry('current_product');
    }

    /**
     * Create POST data for ajax request
     *
     * @return string
     */
    public function getPostData()
    {
       return $this->jsonHelper->jsonEncode(
           [
               'fullActionName' => $this->request->getFullActionName(),
                'product_id' => $this->getProduct()->getId()
           ]
       );
    }

}