<?php

namespace Borisperevyazko\GoogleMarkup\Model;

use Borisperevyazko\GoogleMarkup\Model\AbstractType;

class ProductType extends AbstractType
{

    const DEFINE_TYPE = 'Product';


    public function __construct()
    {
        parent::__construct();
        $this->addType();
    }

    /**
     * {@inheritdoc}
     */
    public function addType()
    {
        $this->type = static::DEFINE_TYPE;
    }
}