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

    /**
     * Holds Restaurant ID
     * 
     * @var int
     */
    private int $restaurant_id;

    /**
     * Holds Phone
     * 
     * @var string
     */
    private string $phone;

    public function __construct(string $username, string $password, int $restaurant_id, string $phone = "")
    {
        $this->username = $username;
        $this->password = $password;
        $this->restaurant_id = $restaurant_id;
        $this->phone = $phone;
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
