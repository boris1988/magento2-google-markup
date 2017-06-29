<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProductType
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
class ProductType extends AbstractType
{

    const DEFINE_TYPE = 'Product';

    const DEFINE_PRODUCT_NAME        = 'name';
    const DEFINE_PRODUCT_DESCRIPTION = 'description';
    const DEFINE_PRODUCT_IMAGE       = 'image';
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

    /**
     * Catalog product model
     *
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $store;

    /**
     * ProductType constructor
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Registry $registry
     * @param StoreManagerInterface $store
     * @param array $data
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Registry $registry,
        StoreManagerInterface $store,
        $data
    ) {
        parent::__construct();
        $this->addType();
        $this->registry = $registry;
        $this->productRepository = $productRepository;
        $this->store = $store;
        if (isset($data['product_id'])) {
            $this->loadProduct($data['product_id']);
        }
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
        if (!$product) {
            return $this;
        }
        $this->addProperty(parent::DEFINE_CONTEXT_KEY, $this->getContext())
            ->addProperty(parent::DEFINE_TYPE_KEY, $this->getType())
            ->addProperty(static::DEFINE_PRODUCT_NAME, $product->getName())
            ->addProperty(static::DEFINE_PRODUCT_IMAGE, $this->getProductImage())
            ->addProperty(static::DEFINE_PRODUCT_DESCRIPTION, $product->getShortDescription())
            ->addProperty(static::DEFINE_PRODUCT_SKU, $product->getSku());

        return $this;
    }

    /**
     * Create link with product image
     *
     * @return string
     */
    protected function getProductImage()
    {
        return $this->store->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA
        ) . 'catalog/product' . $this->getProduct()->getImage();
    }

    /**
     * Load product model with data by passed id.
     * Return false if product was not loaded or has incorrect status.
     *
     * @param int $productId
     * @return bool|CatalogProduct
     */
    protected function loadProduct($productId)
    {
        if (!$productId) {
            return false;
        }

        try {
            $product = $this->productRepository->getById($productId);
            if (!$product->isVisibleInCatalog() || !$product->isVisibleInSiteVisibility()) {
                throw new NoSuchEntityException();
            }
        } catch (NoSuchEntityException $noEntityException) {
            return false;
        }

        $this->registry->register('current_product', $product);

        return $product;
    }
}