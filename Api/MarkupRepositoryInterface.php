<?php

namespace Borisperevyazko\GoogleMarkup\Api;

use Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface;
/**
 * Interface MarkupRepositoryInterface
 *
 * @author Boris Perevyazko <borisperevyazko@gmail.com>
 */
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
     * @param JsonLdTypeInterface $object
     * @return array
     */
    public function getProperties(JsonLdTypeInterface $object);
}