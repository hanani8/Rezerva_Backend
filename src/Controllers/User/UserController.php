<?php

/**
 * The User Class represents a User Object.
 * The UserAuth Class is for handling authentication, and retrieving profile data of the user
 * The UserRepository Class is for interacting with the Users Table in DB
 */

declare(strict_types=1);

/**
 * Controller for all api endpoints related to User
 */
class UserController
{
    private UserRepository $userRepository;
    private Auth $auth;

    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->auth = new Auth($session);

        $this->userRepository = new UserRepository($db);
    }

    /**
     * Method to login a user.
     *
     * @param [type] $route
     * @param [type] $parameters
     * @return void
     */
    public function loginAction($route, $parameters)
    {
        $restaurant_id = $_POST["restaurant_id"];

        $username = strtolower($_POST["username"]);

        $password = $_POST["password"];

        /**
         * Checking if restaurant_id, username and password is provided.
         */
        if ((empty($restaurant_id) || empty($username)) || empty($password)) {
            http_response_code(400);
            return new ReturnType(true, "INSUFFICIENT_DETAILS_FOR_LOGIN");
        }

        $username = $restaurant_id . $username;

        $user = new User($username, $password, intval($restaurant_id));

        // Repositories are something to be used to access data. They themselves shouldn't be able to doing anything.

        $result = $this->auth->login($user, $this->userRepository);

        return $result;
    }

    public function logoutAction($route, $parameters)
    {
        $result = $this->auth->logout();

        return $result;
    }

    public function isLoggedInAction($route, $parameters)
    {
        $result = $this->auth->isLoggedIn();

        return $result;
    }

    public function getProfileAction($route, $parameters)
    {
        $userid = $this->auth->getID();

        $username = $this->auth->getUsername();

        $restaurant_id = $this->auth->getRestaurantID();

        $data = new stdClass();

        $data->userid = $userid;

        $data->username = $username;

        $data->restaurant_id = $restaurant_id;

        return new ReturnType(false, "USER_DATA", $data);
    }

    public function CreateUserAction($route, $parameters)
    {
        /**
         * All the POST data needed for User Creation
         */
        $allNecessaryPostVariables = [
            "username",
            "password",
            "restaurant_id"
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

        $username = strtolower($_POST["username"]);

        $restaurant_id = $_POST["restaurant_id"];

        $username = $restaurant_id . $username;

        $password = $_POST["password"];

        $restaurant_id = intval($_POST["restaurant_id"]);

        $phone = "";

        if (array_key_exists("phone", $_POST) == true) {
            $phone = $_POST["phone"];
        }

        $user = new User($username, $password, $restaurant_id, $phone);

        return $this->userRepository->create($user);
    }
}
