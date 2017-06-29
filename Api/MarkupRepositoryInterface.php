<?php

namespace Borisperevyazko\GoogleMarkup\Api;

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
     * @param \Borisperevyazko\GoogleMarkup\Api\JsonLdTypeInterface $object
     * @return array
     */
    public function getProperties(JsonLdTypeInterface $object);
}