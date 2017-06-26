<?php

namespace Borisperevyazko\GoogleMarkup\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;

class JsonLd extends Action
{

    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {

        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();
        return $result->setData(['success' => true]);
    }
}