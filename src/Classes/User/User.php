<?php

/**
 * Represents a User Object. Used to Create a User, Login the User.
 */

declare(strict_types=1);

class User implements UserInterface
{
    /**
     * Holds User ID
     *
     * @var integer
     */
    private int $user_id;

    /**
     * Holds Username
     *
     * @var string
     */
    private string $username;

    /**
     * Holds Password
     *
     * @var string
     */
    private string $password;

    public function __construct(int $user_id, string $username, string $password)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->password = $password;
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
