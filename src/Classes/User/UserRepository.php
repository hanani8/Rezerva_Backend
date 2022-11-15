<?php

/**
 * Reponsibility: Used to interface with the Users Table.
 */

declare(strict_types=1);

/**
 * A layer between Users Table in the Database, and User Controller, other Classes.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Database
     *
     * @var Database
     */
    private Database $db;

    /**
     * Constructor Function
     *
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Read a row from user table in DB, based on the provided user_id or username
     *
     * @param string $username
     * @return ReturnType
     */
    public function read(string $username = ""): ReturnType
    {
        $prepared_statement = 'SELECT * FROM "Rezerva"."User" where username = ?';

        $values = array($username);

        $resultOfDBOperation = $this->db->query($prepared_statement, $values);

        return $resultOfDBOperation;
    }

    public function create(User $user, int $restaurant_id)
    {
    }
}
