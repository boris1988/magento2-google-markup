<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Helper\Config;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\ConfigurableProduct\Pricing\Price\PriceResolverInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\ConfigurableProduct\Pricing\Price\ConfigurableOptionsProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Review\Model\ReviewFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProductType
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
class ProductType extends AbstractType
{

    const DEFINE_TYPE                               = 'Product';
    const DEFINE_PRODUCT_NAME                       = 'name';
    const DEFINE_PRODUCT_DESCRIPTION                = 'description';
    const DEFINE_PRODUCT_IMAGE                      = 'image';
    const DEFINE_PRODUCT_BRAND                      = 'brand';
    const DEFINE_PRODUCT_SKU                        = 'sku';
    const DEFINE_PRODUCT_OFFERS                     = 'offers';
    const DEFINE_PRODUCT_OFFERS_KEY                 = 'Offer';
    const DEFINE_PRODUCT_AGGREGATEOFFERS_KEY        = 'AggregateOffer';
    const DEFINE_PRODUCT_OFFERS_PRICE               = 'price';
    const DEFINE_PRODUCT_OFFERS_LOW_PRICE           = 'lowPrice';
    const DEFINE_PRODUCT_OFFERS_HIGH_PRICE          = 'highPrice';
    const DEFINE_PRODUCT_OFFERS_CURRENCY            = 'priceCurrency';
    const DEFINE_PRODUCT_OFFER_ITEM_CONDITION       = 'itemCondition';
    const DEFINE_PRODUCT_OFFER_ITEM_CONDITION_VALUE = 'http://schema.org/NewCondition';
    const DEFINE_PRODUCT_OFFER_ITEM_URL             = 'url';
    const DEFINE_PRODUCT_OFFER_ITEM_AVAILABILITY    = 'availability';
    const DEFINE_PRODUCT_AGGREGATE_RATING           = 'AggregateRating';
    const DEFINE_PRODUCT_AGGREGATE_RATING_KEY       = 'aggregateRating';
    const DEFINE_PRODUCT_RATING_VALUE               = 'ratingValue';
    const DEFINE_PRODUCT_RATING_COUNT               = 'reviewCount';
    const DEFINE_OFFER_INSTOCK                      = "http://schema.org/InStock";
    const DEFINE_OFFER_OUTSTOCK                     = "http://schema.org/OutOfStock";

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
     * @var ConfigurableOptionsProviderInterface
     */
    private $configurableOptionsProvider;

    /**
     * @var PriceResolverInterface
     */
    protected $priceResolver;

    /**
     * @var ReviewFactory
     */
    protected $rewiewFactory;

    /**
     * ProductType constructor
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Registry $registry
     * @param StoreManagerInterface $store
     * @param ReviewFactory $reviewFactory
     * @param Config $config
     * @param $data
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Registry $registry,
        StoreManagerInterface $store,
        ReviewFactory $reviewFactory,
        Config $config,
        $data
    ) {
        parent::__construct($config);
        $this->addType();
        $this->registry = $registry;
        $this->productRepository = $productRepository;
        $this->store = $store;
        $this->rewiewFactory = $reviewFactory;
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
        $this->addProperty(AbstractType::DEFINE_CONTEXT_KEY, $this->getContext())
            ->addProperty(AbstractType::DEFINE_TYPE_KEY, $this->getType())
            ->addProperty(static::DEFINE_PRODUCT_NAME, $product->getName())
            ->addProperty(static::DEFINE_PRODUCT_IMAGE, $this->getProductImage())
            ->addProperty(static::DEFINE_PRODUCT_DESCRIPTION, $product->getShortDescription())
            ->addProperty(static::DEFINE_PRODUCT_SKU, $product->getSku());

        if ($this->configHelper->isShowOffer()) {
            $this->createOfferProperty();
        }
        if ($this->configHelper->isShowAggregateRating() && $this->isProductHasReviews()) {
            $this->addProperty(static::DEFINE_PRODUCT_AGGREGATE_RATING_KEY, $this->getAggregateRating());
        }

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

    /**
     * Create array with product info
     *
     * @return array
     */
    protected function getOffers()
    {
        $offers = [static::DEFINE_TYPE_KEY => static::DEFINE_PRODUCT_OFFERS_KEY];
        $offers[ static::DEFINE_PRODUCT_OFFERS_PRICE ] = $this->getProduct()->getPriceInfo()->getPrice('final_price'
        )->getAmount()->getValue();

        $offers[ static::DEFINE_PRODUCT_OFFERS_CURRENCY ] = $this->store->getStore()->getBaseCurrency()
            ->getCurrencyCode();
        $offers[ static::DEFINE_PRODUCT_OFFER_ITEM_CONDITION ] = static::DEFINE_PRODUCT_OFFER_ITEM_CONDITION_VALUE;
        $offers[ static::DEFINE_PRODUCT_OFFER_ITEM_URL ] = $this->getProduct()->getProductUrl();
        $offers[ static::DEFINE_PRODUCT_OFFER_ITEM_AVAILABILITY ] = $this->getAvailability();

        return $offers;
    }

    /**
     * Create array with product info
     *
     * @return array
     */
    protected function getAggregateOffer()
    {
        $offers = [static::DEFINE_TYPE_KEY => static::DEFINE_PRODUCT_AGGREGATEOFFERS_KEY];
        $offers[ static::DEFINE_PRODUCT_OFFERS_LOW_PRICE ] = $this->getLowPrice();
        $offers[ static::DEFINE_PRODUCT_OFFERS_HIGH_PRICE ] = $this->getHighPrice();
        $offers[ static::DEFINE_PRODUCT_OFFERS_CURRENCY ] = $this->store->getStore()->getBaseCurrency()
            ->getCurrencyCode();

        return $offers;
    }

    /**
     * Get aggregate rating
     *
     * @return array
     */
    protected function getAggregateRating()
    {
        $rating = [static::DEFINE_TYPE_KEY => static::DEFINE_PRODUCT_AGGREGATE_RATING];
        $this->rewiewFactory->create()->getEntitySummary($this->getProduct(), $this->store->getStore()->getId());
        $ratingSummary = $this->getProduct()->getRatingSummary();
        if (null === $ratingSummary) {
            return [];
        }
        if ($ratingSummary->getReviewsCount() == 0) {
            return [];
        }

        $percentValue = number_format(($ratingSummary->getRatingSummary() * 5 / 100), 1);
        $rating[ static::DEFINE_PRODUCT_RATING_VALUE ] = $percentValue;
        $rating[ static::DEFINE_PRODUCT_RATING_COUNT ] = $ratingSummary->getReviewsCount();

        return $rating;
    }

    /**
     * Get Lowest price
     *
     * @return string
     */
    protected function getLowPrice()
    {
        return number_format($this->getProduct()->getPriceInfo()->getPrice('final_price')->getAmount()->getValue(), 2);
    }

    /**
     * Get higher price
     *
     * @return string
     */
    protected function getHighPrice()
    {
        $price = null;
        foreach ($this->getConfigurableOptionsProvider()->getProducts($this->getProduct()) as $subProduct) {
            $productPrice = $subProduct->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
            $price = $price ? max($price, $productPrice) : $productPrice;
        }

        return number_format($price, 2);
    }

    /**
     * Get product Availability
     *
     * @return string
     */
    protected function getAvailability()
    {
        return $this->getProduct()->isInStock() && $this->getProduct()->isSalable()
            ? static::DEFINE_OFFER_INSTOCK
            : static::DEFINE_OFFER_OUTSTOCK;
    }

    /**
     * Get provider
     *
     * @return \Magento\ConfigurableProduct\Pricing\Price\ConfigurableOptionsProviderInterface
     * @deprecated
     */
    protected function getConfigurableOptionsProvider()
    {
        if (null === $this->configurableOptionsProvider) {
            $this->configurableOptionsProvider = ObjectManager::getInstance()
                ->get(ConfigurableOptionsProviderInterface::class);
        }

        return $this->configurableOptionsProvider;
    }

    /**
     * Create Offer property
     *
     * @return $this
     */
    protected function createOfferProperty()
    {
        if ($this->getProduct()->getTypeId() == Configurable::TYPE_CODE) {
            $this->addProperty(static::DEFINE_PRODUCT_OFFERS, $this->getAggregateOffer());
        } else {
            $this->addProperty(static::DEFINE_PRODUCT_OFFERS, $this->getOffers());
        }

        return $this;
    }

    /**
     * Check if product has reviews
     *
     * @return bool
     */
    protected function isProductHasReviews()
    {
        $this->rewiewFactory->create()->getEntitySummary($this->getProduct(), $this->store->getStore()->getId());
        $ratingSummary = $this->getProduct()->getRatingSummary();

        return $ratingSummary->getReviewsCount() > 0 ? true : false;
    }
}