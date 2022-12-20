<?php

/**
 * Responsibility: Used to fetch all restaurants, instead of a particular restaurant, from the Restaurant Table
 */

declare(strict_types=1);
/**
 * Serves the same purpose as RestaurantRepository, but operates on collection of restaurants instead one.
 */
interface AllRestaurantsQueryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Database $db);

    /**
     * Fetch all Brands
     *
     * @return ReturnType
     */
    public function fetch(int $admin_id): ReturnType;
}
