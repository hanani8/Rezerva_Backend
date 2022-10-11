<?php

/**
 * Responsibility: Set Values in _SESSION and remove them.
 * php version 8.1
 */

declare(strict_types=1);

interface SessionInterface
{
    /**
     * Constructor function for Session
     */
    public function __construct();

    /**
     * To set value in $_SESSION array
     *
     * @param string $name
     * @param integer|string $value
     * @return void
     */
    public function __set(string $name, int|string $value): void;

    /**
     * To get value from $_SESSION array
     *
     * @param string $name
     * @return integer|string
     */
    public function __get(string $name): int|string;

    /**
     * To unset all values from session array, and destroy the session
     *
     * @return void
     */
    public function remove(): void;

    /**
     * Returns the is_logged_in variable
     *
     * @return boolean
     */
    public function is_logged_in(): bool;
}

// https://stackoverflow.com/a/44960660/11887766
