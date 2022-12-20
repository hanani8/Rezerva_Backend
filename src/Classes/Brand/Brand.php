<?php

/**
 * Represents a Brand Object. Used to Create a Brand.
 */

declare(strict_types=1);

class Brand implements BrandInterface
{
    /**
     * Holds Brand Name 
     *
     * @var string
     */
    private string $brand_name;

    public function __construct(string $brand_name)
    {
        $this->brand_name = $brand_name;
    }

    /**
     * Gets the values of private variables.
     *
     * @param String $name
     * @return void
     */
    public function __get(String $name): int|string
    {
        return $this->$name;
    }
}
