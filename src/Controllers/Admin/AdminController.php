<?php

/**
 * The AdminRepository Class is for interacting with the Admin Table in DB
 */

declare(strict_types=1);

/**
 * Controller for all api endpoints related to Admin
 */
class AdminController
{
    private AdminRepository $adminRepository; // To read, create a admin

    private Auth $auth;

    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->adminRepository = new AdminRepository($db);

        $this->auth = new Auth($session);
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
        $brand_id = $_POST["brand_id"];

        $username = strtolower($_POST["username"]);

        $password = $_POST["password"];

        /**
         * Checking if restaurant_id, username and password is provided.
         */
        if ((empty($brand_id) || empty($username)) || empty($password)) {
            http_response_code(400);
            return new ReturnType(true, "INSUFFICIENT_DETAILS_FOR_LOGIN");
        }

        $username = $brand_id . $username;

        $user = new User($username, $password, intval($brand_id));

        // Repositories are something to be used to access data. They themselves shouldn't be able to doing anything.

        $result = $this->auth->login($user, $this->adminRepository);

        return $result;
    }

    // Creating ADMIN 

    public function CreateAdminAction($route, $parameters)
    {
        /**
         * All the POST data needed for Admin Creation
         */
        $allNecessaryPostVariables = [
            "username",
            "password",
            "phone",
            "brand_id"
        ];

        /**
         * Check if all the necessary variables in the $_POST array are present.
         * In case, they don't. Throw a insufficient data error.
         */
        foreach ($allNecessaryPostVariables as $variable) {
            if (array_key_exists($variable, $_POST) == false) {
                http_response_code(400);
                return new ReturnType(true, "INSUFFICIENT_DATA");
            }
        }
        // Adding brand_id infront of the username
        $username = $_POST["brand_id"] . $_POST["username"];

        $password = $_POST["password"];

        $phone = $_POST["phone"];

        try {
            $brand_id = intval($_POST["brand_id"]);
        } catch (Throwable $th) {
            return $th;
        }

        $admin = new Admin($username, $password, $phone, $brand_id);

        return $this->adminRepository->create($admin);
    }
}
