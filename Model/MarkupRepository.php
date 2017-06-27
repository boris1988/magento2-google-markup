<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;
use Borisperevyazko\GoogleMarkup\Api\MarkupRepositoryInterface;

class MarkupRepository implements MarkupRepositoryInterface
{
    private $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->_objectManager = $objectmanager;
    }

    /**
     * {@inheritdoc}
     */
    public function load($fullActionName)
    {

        switch ($fullActionName) {
            case 'catalog_product_view':
                $jsonLd = $this->_objectManager->create('Borisperevyazko\GoogleMarkup\Model\ProductType');
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
        $jsonLd->initProperties()->getProperties();
    }

}