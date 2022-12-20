<?php

/**
 * Handles User/Admin Authentication, and stores commonly required data
 * pertaining to a user session.
 * php version 8.1
 * 
 * @category
 * @package
 * @author   Jai Hanani <jaihanani8@gmail.com>
 * @license
 * @link
 */

declare(strict_types=1);

/**
 * Interface for Auth Class.
 */
interface AuthInterface
{
    /**
     * Construct a UserAuth Object.
     *
     * @param Session $session
     */
    public function __construct(
        Session $session
    );

    /**
     * Checks if a user is logged in.
     *
     * @return ReturnType
     */
    public function isLoggedIn(): ReturnType;

    /**
     * Logs in a user.
     *
     * @param User|Admin $user
     * @param UserRepository $user_repository
     * @return void
     */
    public function login(User|Admin $user, UserRepository|AdminRepository $user_repository): ReturnType;

    /**
     * Logs out a user.
     *
     * @return void
     */
    public function logout(): ReturnType;

    /**
     * Gets the Restaurant ID from Session $session
     *
     * @return void
     */
    public function getRestaurantID(): int;

    /**
     * Gets the Brand ID from Session $session
     * 
     * @return int;
     */
    public function getBrandID(): int;

    /**
     * Gets the Username from Session $session
     *
     * @return void
     */
    public function getUsername(): String;
}
