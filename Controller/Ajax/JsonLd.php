<?php

namespace Borisperevyazko\GoogleMarkup\Controller\Ajax;

use Borisperevyazko\GoogleMarkup\Api\MarkupRepositoryInterface;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\JsonFactory;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;

class JsonLd extends Action
{

    const DEFINE_POST_PARAM = 'fullActionName';
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var MarkupRepositoryInterface
     */
    protected $repositoryInterface;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        HttpRequest $request,
        MarkupRepositoryInterface $repositoryInterface
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->jsonjdObject = $resultJsonFactory;
        $this->repositoryInterface = $repositoryInterface;
    }

    public function execute()
    {
        $jsonLd = $this->repositoryInterface->load($this->request->getParam(static::DEFINE_POST_PARAM));
        $data = ['success' => false];
        if (null !== $jsonLd) {
            $data['success'] = false;
            $data['properies'] = $this->repositoryInterface->getProperties($jsonLd);
        }

        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        return $result->setData($data);
    }
}