<?php

/**
 * Represents a User Object. Used to Create a User, Login the User.
 */

declare(strict_types=1);

/**
 * Interface for User Class.
 */
interface UserInterface
{
    /**Constructor Function */
    public function __construct(
        string $username,
        string $password,
        int $restaurant_id,
        string $phone
    );

    /**
     * Gets the values of private variables
     *
     * @param String $name
     * @return integer|string
     */
    public function __get(String $name): int|string;
}
