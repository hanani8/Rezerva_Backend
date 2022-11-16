<?php

/**
 * The RestaurantRepository Class is for interacting with the Restaurant Table in DB
 */

declare(strict_types=1);

/**
 * Controller for all api endpoints related to User
 */
class RestaurantController
{
    private RestaurantRepository $restaurantRepository;

    private UserAuth $userAuth;



    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->restaurantRepository = new RestaurantRepository($db);

        $this->userAuth = new UserAuth($session);
    }

    public function ReadRestaurantAction($route, $parameters)
    {

        $restaurant_id = intval($parameters["restaurant_id"]);

        return $this->restaurantRepository->read($restaurant_id);
    }

    public function ReadRestaurantWithoutParameterAction($route, $parameters)
    {

        $restaurant_id = $this->userAuth->getRestaurantID();

        return $this->restaurantRepository->read($restaurant_id);
    }
}
