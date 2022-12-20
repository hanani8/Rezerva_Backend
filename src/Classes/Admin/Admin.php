<?php

/**
 * Represents an Admin Object. Used to Create an Admin.
 */

declare(strict_types=1);

class Admin implements AdminInterface
{
    /**
     * Holds Admin Username 
     *
     * @var string
     */
    private string $username;

    /**
     * Holds Admin Password 
     *
     * @var string
     */
    private string $password;

    /**
     * Holds Admin's Phone
     *
     * @var string
     */
    private string $phone;

    /**
     * brand_id
     *
     * @var integer
     */
    private int $brand_id;

    public function __construct(string $username, string $password, string $phone, int $brand_id)
    {
        $this->username = $username;
        $this->password = $password;
        $this->phone = $phone;
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
