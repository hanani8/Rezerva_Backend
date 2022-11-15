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
    private UserAuth $userAuth;

    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->userAuth = new UserAuth($session);

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
        if((empty($restaurant_id) || empty($username)) || empty($password))
        {
            http_response_code(400);
            return new ReturnType(true, "INSUFFICIENT_DETAILS_FOR_LOGIN");
        }

        $username = $restaurant_id . $username;

        $user = new User($username, $password);

        // Repositories are something to be used to access data. They themselves shouldn't be able to doing anything.

        $result = $this->userAuth->login($user, $this->userRepository);

        return $result;
    }

    public function logoutAction($route, $parameters)
    {
        $result = $this->userAuth->logout();

        return $result;
    }

    public function isLoggedInAction($route, $parameters)
    {
        $result = $this->userAuth->isLoggedIn();

        return $result;
    }

    public function getProfileAction($route, $parameters)
    {
           $userid = $this->userAuth->getUserID();

           $username = $this->userAuth->getUsername();

           $restaurant_id = $this->userAuth->getRestaurantID();

           $data = new stdClass();

           $data->userid = $userid;

           $data->username = $username;

           $data->restaurant_id = $restaurant_id;

           return new ReturnType(false, "USER_DATA", $data);
    }
}
