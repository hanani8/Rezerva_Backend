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

    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db)
    {
        $this->restaurantRepository = new RestaurantRepository($db);
    }

    public function ReadRestaurantAction($route, $parameters)
    {

           $restaurant_id = intval($parameters["restaurant_id"]);

           return $this->restaurantRepository->read($restaurant_id);
    }
}
