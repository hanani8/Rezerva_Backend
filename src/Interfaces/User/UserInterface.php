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
        int $userid,
        string $username,
        string $password,
    );

    /**
     * Gets the values of private variables
     *
     * @param String $name
     * @return integer|string
     */
    public function __get(String $name): int|string;
}
