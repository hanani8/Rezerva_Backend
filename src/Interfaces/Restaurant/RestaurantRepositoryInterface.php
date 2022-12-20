<?php

/**
 * Responsibility: Used to interact with the Restaurant table in the Database
 */

declare(strict_types=1);
/**
 * Layer between Restaurant Table in the DB, and Restaurant Controller
 */
interface RestaurantRepositoryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     */
    public function __construct(Database $db, Session $session);

    /**
     * Reads a Restaurant from DB
     *
     * @param integer $restaurant_id
     * @return ReturnType
     */
    public function read(int $restaurant_id): ReturnType;

    /**
     * Creates a Restaurant from DB
     * 
     * @param Restaurant $restaurant
     * @return ReturnType
     */
    public function create(Restaurant $restaurant): ReturnType;

    /**
     * Updates a Restaurant from DB
     * 
     */
    public function update(array $names, array $values, int $restaurant_id): ReturnType;
}
