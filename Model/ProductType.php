<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;

use Borisperevyazko\GoogleMarkup\Model\AbstractType;

class ProductType extends AbstractType
{

    const DEFINE_TYPE = 'Product';

    const DEFINE_PRODUCT_NAME        = 'name';
    const DEFINE_PRODUCT_DESCRIPTION = 'description';
    const DEFINE_PRODUCT_BRAND       = 'brand';
    const DEFINE_PRODUCT_SKU         = 'sku';

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ProductInterface
     */
    protected $product;

    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
        parent::__construct();
        $this->addType();
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
     * @return mixed
     */
    protected function getProduct()
    {
        if (null === $this->product) {
            $this->product = $this->registry->registry('current_product');
        }

        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    public function initProperties()
    {
        $product = $this->getProduct();
        $this->addProperty(
            static::DEFINE_PRODUCT_NAME,
            $product->getName()
        )->addProperty(static::DEFINE_PRODUCT_DESCRIPTION,
            $product->getDescription()
        )->addProperty(static::DEFINE_PRODUCT_BRAND,
            $product->getManufacturer()
        );

        return $this;
    }
}