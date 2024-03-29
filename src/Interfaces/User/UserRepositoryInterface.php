<?php

/**
 * Reponsibility: Used to interface with the Users Table.
 */

declare(strict_types=1);

/**
 * A layer between Users Table in the Database, and User Controller, other Classes.
 */
interface UserRepositoryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     */
    public function __construct(Database $db);


    /**
     * Returns a PDOStatement, which may contain relevant user details, based on the provided user_id or username
     *
     * @param integer $userid
     * @param string $username
     * @return ReturnType
     */
    public function read(string $username = ""): ReturnType;

    public function create(User $user);
}
