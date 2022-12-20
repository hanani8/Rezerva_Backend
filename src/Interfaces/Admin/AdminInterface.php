<?php

/**
 * Represents a Admin Object. Used to Create a Admin.
 */

declare(strict_types=1);

/**
 * Interface for Admin Class.
 */
interface AdminInterface
{
    /**Constructor Function */
    public function __construct(
        string $username,
        string $password,
        string $phone,
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
