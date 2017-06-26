<?php

namespace Borisperevyazko\GoogleMarkup\Api;

interface JsonLdTypeInterface
{
    /**
     * Add type
     *
     * @return void
     */
    public function addType();

    /**
     * Add context
     *
     * @return void
     */
    public function addContext();

    /**
     * Get contect value
     *
     * @return string
     */
    public function getContext();

    /**
     * @return string
     */
    public function getType();

    /**
     * Add propery
     *
     * @param string $key
     * @param string|array $value
     * @return void
     */
    public function addProperty($key, $value);
}