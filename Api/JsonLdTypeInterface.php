<?php

namespace Borisperevyazko\GoogleMarkup\Api;

interface JsonLdTypeInterface
{
    /**
     * Add type
     *
     * @return $this
     */
    public function addType();

    /**
     * Add context
     *
     * @return $this
     */
    public function addContext();

    /**
     * Get contect value
     *
     * @return string
     */
    public function getContext();

    /**
     * @return mixed
     */
    public function getType();

    /**
     * Add propery
     *
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function addProperty($key, $value);

    /**
     * Init markup object
     *
     * @return $this
     */
    public function initProperties();

    /**
     * Get markup object
     *
     * @return array
     */
    public function getProperties();
}