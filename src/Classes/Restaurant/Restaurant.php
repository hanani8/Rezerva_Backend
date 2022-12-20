<?php

/**
 * Represents an Restaurant Object. Used to Create an Restaurant.
 */

declare(strict_types=1);

class Restaurant implements RestaurantInterface
{
    /**
     * Holds restaurant name 
     *
     * @var string
     */
    private string $restaurant_name;

    /**
     * Holds restaurant location 
     *
     * @var string
     */
    private string $location;

    /**
     * brand_id
     *
     * @var integer
     */
    private int $brand_id;

    public function __construct(string $restaurant_name, string $location, int $brand_id = 0)
    {
        $this->restaurant_name = $restaurant_name;
        $this->location = $location;
        $this->brand_id = $brand_id;
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
