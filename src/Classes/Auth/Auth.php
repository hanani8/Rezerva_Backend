<?php

/**
 * Responsibility: 
 * Used to Login a User|Admin, 
 * Get LOGGED_IN status, 
 * Get User ID, 
 * Get Restaurant ID,
 * Get Brand ID,
 * Logout
 */

declare(strict_types=1);

class Auth implements AuthInterface
{
    /**
     * Holds the Session object
     *
     * @var Session
     */
    private Session $session;

    /**
     * Constructor function
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Logs the user in
     *
     * @return ReturnType
     */
    public function login(User|Admin $user, UserRepository|AdminRepository $repository): ReturnType
    {
        /**
         * UserRepository or AdminRepository
         */
        $userOrAdmin = 0;

        if (get_class($repository) == "AdminRepository") {
            $userOrAdmin = 1;
        }
        /**
         * To hold the result of DB operation from User Repository
         */
        $resultOfDBOperation = $repository->read($user->username);

        /**
         * PDO Statement returned by the Database
         */
        $PDOStatement = $resultOfDBOperation->data->statement;

        /**
         * To hold the row returned by the DB
         */
        $rowFromUsersTableInDB = [];

        /**
         *  Salt
         */
        $salt = "a#o*P+_j8";
        /**
         * To hold the user provided password
         */
        $userProvidedPassword = hash("md5", $salt . $user->password);

        /**
         * Checking if the DB Operation has failed
         */
        if ($resultOfDBOperation->error == true) {
            $PDOStatement = null;

            http_response_code(500);
            return new ReturnType(true, "STMT_FAILED");
        }

        /**
         * Checking if the DB Operation did not return any row
         */
        if ($PDOStatement->rowCount() == 0) {
            $PDOStatement = null;

            http_response_code(404);
            return new ReturnType(true, "USER_NOT_FOUND");
        }

        /**
         * Fetching all the rows from the DB Operation
         */
        $rowFromUsersTableInDB = $PDOStatement->fetch(PDO::FETCH_ASSOC);

        /**
         * Checking if the user-provided password matches the password stored in the Database.
         */
        if ($rowFromUsersTableInDB["password"] != $userProvidedPassword) {
            $PDOStatement = null;

            http_response_code(404);
            return new ReturnType(true, "WRONG_PASSWORD");
        } elseif ($rowFromUsersTableInDB["password"] == $userProvidedPassword) {
            $PDOStatement = null;

            $data = new stdClass();

            $data->session_id = session_id();

            if ($userOrAdmin == 0) {
              
                $this->session->id = $rowFromUsersTableInDB["user_id"];

                $this->session->brand_id = $rowFromUsersTableInDB["brand_id"];

                $this->session->restaurant_id = $rowFromUsersTableInDB["restaurant_id"];

                $this->session->username = $rowFromUsersTableInDB["username"];

                $this->session->role = "USER";

            } else if ($userOrAdmin == 1) {
                
                $this->session->id = $rowFromUsersTableInDB["admin_id"];

                // $this->session->restaurant_id = $rowFromUsersTableInDB["restaurant_id"];

                $this->session->brand_id = $rowFromUsersTableInDB["brand_id"];

                $this->session->username = $rowFromUsersTableInDB["username"];

                $this->session->role = "ADMIN";
            
            }

            http_response_code(200);
            return new ReturnType(false, "LOGGED_IN", $data);
        } else {
            $PDOStatement = null;

            http_response_code(500);
            return new ReturnType(true, "UNRECOGNIZED_ERROR");
        }
    }

    /**
     * Checks if a user is logged in, using Session $session object.
     *
     * @return ReturnType
     */
    public function isLoggedIn(): ReturnType
    {
        $result = $this->session->is_logged_in();

        $data = new stdClass();

        if ($result == true) {
            http_response_code(200);
            $role = $this->session->role;
            
            if($role == 1) {
                $role = "USER";
            } else if($role == 2) {
                $role = "ADMIN";
            } else if($role == 3) {
                $role = "SUPERADMIN";
            }

            $data->role = $role;

            return new ReturnType(false, "LOGGED_IN", $data);
        } else {
            http_response_code(200);
            return new ReturnType(false, "NOT_LOGGED_IN");
        }
    }

    /**
     * Logs a user out, by destroying all session data.
     *
     * @return ReturnType
     */
    public function logout(): ReturnType
    {
        $this->session->remove();

        http_response_code(200);
        return new ReturnType(false, "LOGGED_OUT");
    }

    /**
     * Gets the Restaurant ID from Session $session
     *
     * @return integer
     */
    public function getRestaurantID(): int
    {
        return $this->session->restaurant_id;
    }

    /**
     * Gets the User ID for currently logged in user from Session $session
     *
     * @return integer
     */
    public function getID(): int
    {
        return $this->session->id;
    }

    /**
     * Gets the ROLE from $_SESSION Array
     *
     * @return String
     */
    public function getRole(): String
    {
        return $this->session->role;
    }

    /**
     * Gets the Username of the currently logged in user from Session $session;
     *
     * @return String
     */
    public function getUsername(): String
    {
        return $this->session->username;
    }

    /**
     * Gets the Brand ID of the currently logged in user from Session $session;
     * 
     * @return int
     */

    public function getBrandID(): int
    {
        return $this->session->brand_id;
    }
}
