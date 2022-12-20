<?php

/**
 * Represents a Restaurant Object. Used to Create a Restaurant.
 */

declare(strict_types=1);

/**
 * Interface for Restaurant Class.
 */
interface RestaurantInterface
{
    /**Constructor Function */
    public function __construct(
        string $restaurant_name,
        string $location,
        int $brand_id
    );

    /**
     * Gets the values of private variables
     *
     * @param String $name
     * @return integer|string
     */
    public function __get(String $name): int|string;
}
