<?php

declare(strict_types=1);

/**
 * Controller for all api endpoints related to Superadmin login
 */
class SuperadminController
{

    public Session $session;

    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->session = $session;
    }

    /**
     * Method to login a admin.
     *
     * @param [type] $route
     * @param [type] $parameters
     * @return void
     */
    public function loginAction($route, $parameters)
    {
        $username = strtolower($_POST["username"]);

        $password = $_POST["password"];

        /**
         * Checking if username and password is provided.
         */
        if ((empty($username)) || empty($password)) {
            http_response_code(400);
            return new ReturnType(true, "INSUFFICIENT_DETAILS_FOR_LOGIN");
        }

        $correct_username = "superadmin";

        $correct_password = "a#o*P+_j8";

        $data = new stdClass();


        if ($username == $correct_username) {
            if ($password == $correct_password) {

                $data->session_id = session_id();

                $this->session->id = "1";

                $this->session->role = "SUPERADMIN";
            } else {
                return new ReturnType(true, "WRONG_PASSWORD");
            }
        } else {
            return new ReturnType(true, "WRONG_USERNAME");
        }

        return new ReturnType(false, "LOGGED_IN", $data);
    }
}
