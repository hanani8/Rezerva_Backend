<?php

/**
 * Responsibility: Used to interact with the Admin table in the Database
 */

declare(strict_types=1);
/**
 * Layer between Admin Table in the DB, and Admin Controller
 */
interface AdminRepositoryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     */
    public function __construct(Database $db);

    /**
     * Creates an Admin for a Brand in DB
     *
     * @param Admin $admin
     * @return ReturnType
     */
    public function create(Admin $admin): ReturnType;

    /**
     * Returns a PDOStatement, which may contain relevant user details, based on the provided username
     *
     * @param string $username
     * @return ReturnType
     */
    public function read(string $username = ""): ReturnType;
}
