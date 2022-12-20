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
        $prepared_statement = 'SELECT * FROM "Rezerva"."User" NATURAL JOIN "Rezerva"."Restaurant" where username = ?';

        $values = array($username);

        $resultOfDBOperation = $this->db->query($prepared_statement, $values);

        return $resultOfDBOperation;
    }

    public function create(User $user)
    {
        $prepared_statement = 'INSERT INTO "Rezerva"."User" (username, password, restaurant_id, phone) VALUES (?,?,?,?) RETURNING user_id';

        /**
         *  Salt
         */
        $salt = "a#o*P+_j8";
        /**
         * To hold the user provided password
         */
        $userProvidedPassword = hash("md5", $salt . $user->password);

        $values = array($user->username, $userProvidedPassword, $user->restaurant_id, $user->phone);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $data = new stdClass();

        if ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->user_id = $row['user_id'];

            http_response_code(201);
            return new ReturnType(false, "CREATE_USER_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "CREATE_USER_FAILED");
        }
    }
}
