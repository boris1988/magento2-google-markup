<?php

namespace Borisperevyazko\GoogleMarkup\Api;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;

interface MarkupRepositoryInterface
{
    /**
     * Init object with correct class
     *
     * @param string $fullActionName
     * @return JsonLdTypeInterface
     */
    public function load($fullActionName);

    /**
     * Get markup object
     *
     * @param \Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface $object
     * @return array
     */
    public function getProperties(JsonLdTypeInterface $object);
}