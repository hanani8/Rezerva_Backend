<?php

/**
 * Represents a Brand Object. Used to Create a Brand.
 */

declare(strict_types=1);

/**
 * Interface for Brand Class.
 */
interface BrandInterface
{
    /**Constructor Function */
    public function __construct(
        string $brand_name,
    );

    /**
     * Gets the values of private variables
     *
     * @param String $name
     * @return integer|string
     */
    public function __get(String $name): int|string;
}
