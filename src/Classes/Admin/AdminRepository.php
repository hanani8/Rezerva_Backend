<?php

declare(strict_types=1);
/**
 * Layer between Admin Table in the DB, and Admin Controller, Other Classes
 */
class AdminRepository implements AdminRepositoryInterface
{
    /**
     * Database
     *
     * @var Database
     */
    private Database $db;

    /**
     * Constructor Function
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Creates a Brand
     *
     * @param Admin $admin
     * @return ReturnType
     */
    public function create(Admin $admin): ReturnType
    {
        $prepared_statement = 'INSERT INTO "Rezerva"."Admin" (username, password, phone, brand_id) VALUES (?,?,?,?) RETURNING admin_id';

        /**
         *  Salt
         */
        $salt = "a#o*P+_j8";
        /**
         * To hold the user provided password
         */
        $userProvidedPassword = hash("md5", $salt . $admin->password);

        $values = array($admin->username, $userProvidedPassword, $admin->phone, $admin->brand_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $data = new stdClass();

        if ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->admin_id = $row['admin_id'];

            http_response_code(201);
            return new ReturnType(false, "CREATE_ADMIN_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "CREATE_ADMIN_FAILED");
        }
    }

    /**
     * Read a row from user table in DB, based on the provided username
     *
     * @param string $username
     * @return ReturnType
     */
    public function read(string $username = ""): ReturnType
    {
        $prepared_statement = 'SELECT * FROM "Rezerva"."Admin" where username = ?';

        $values = array($username);

        $resultOfDBOperation = $this->db->query($prepared_statement, $values);

        return $resultOfDBOperation;
    }
}

// SELECT "username", "user_id", "Restaurant"."restaurant_id", "restaurant_name", "location" 
// FROM "Rezerva"."Restaurant" LEFT JOIN "Rezerva"."User" 
// ON "Rezerva"."User"."restaurant_id" = "Rezerva"."Restaurant"."restaurant_id" 