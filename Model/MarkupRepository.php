<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;
use Borisperevyazko\GoogleMarkup\Api\MarkupRepositoryInterface;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class MarkupRepository
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
class MarkupRepository implements MarkupRepositoryInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $_objectManager;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * MarkupRepository constructor
     *
     * @param ObjectManagerInterface $objectmanager
     * @param HttpRequest $request
     */
    public function __construct(
        ObjectManagerInterface $objectmanager,
        HttpRequest $request
    ) {
        $this->_objectManager = $objectmanager;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function load($fullActionName)
    {

        switch ($fullActionName) {
            case 'catalog_product_view':
                $jsonLd = $this->_objectManager->create('Borisperevyazko\GoogleMarkup\Model\ProductType',
                    ['data' => $this->request->getPostValue()]
                );
                break;

            case 'cms_index_index':
                $jsonLd = $this->_objectManager->create('Borisperevyazko\GoogleMarkup\Model\WebsiteType',
                    ['data' => $this->request->getPostValue()]
                );
                break;

            default:
                $jsonLd = null;
        }

        return $jsonLd;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties(JsonLdTypeInterface $jsonLd)
    {
        return $jsonLd->initProperties()->getProperties();
    }

}