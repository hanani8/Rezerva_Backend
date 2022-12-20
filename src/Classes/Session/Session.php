<?php

// session.use_strict_mode

/**
 * A wrapper class that wraps around $_SESSION array
 */
class Session implements SessionInterface
{
    /**
     * To know if user is authenticated or not.
     *
     * @var bool
     */
    private $logged_in = false;

    /**
     * Constructor function
     */
    public function __construct()
    {
        session_start();

        /**
         * Checking is user/admin/superadmin is already authenticated.
         */
        if(isset($_SESSION['id']))
        {
            $this->logged_in = true;
        } 
        
    }


    /**
     * Sets value in $_SESSION array
     *
     * @param string $name
     * @param integer|string $value
     * @return void
     */
    public function __set(string $name, int|string $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Gets value from $_SESSION array
     */
    public function __get(string $name): int|string|null
    {
        return $_SESSION[$name];
    }

    /**
     * Sets logged_in to false, destroy session, unsets all $_SESSION array variables
     *
     * @return void
     */
    public function remove(): void
    {

        $this->logged_in = 0;
        session_destroy();
        unset($_SESSION);
    }

    /**
     * Unsets all $_SESSION array variables
     */

    public function unset(): void
    {
        unset($_SESSION);
    }

    /**
     * returns $this->logged_in variable.
     *
     * @return int
     */
    public function is_logged_in(): bool
    {
        return $this->logged_in;
    }

}
