<?php

/**
 * The RestaurantRepository Class is for interacting with the Restaurant Table in DB
 */

declare(strict_types=1);

/**
 * Controller for all api endpoints related to Restaurant
 */
class RestaurantController
{
    private RestaurantRepository $restaurantRepository;

    private AllRestaurantsQuery $allRestaurantsQuery;

    private Auth $Auth;



    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->restaurantRepository = new RestaurantRepository($db, $session);

        $this->allRestaurantsQuery = new AllRestaurantsQuery($db, $session);

        $this->auth = new Auth($session);
    }

    public function ReadRestaurantAction($route, $parameters)
    {

        $restaurant_id = intval($parameters["restaurant_id"]);

        return $this->restaurantRepository->read($restaurant_id);
    }

    public function ReadRestaurantWithoutParameterAction($route, $parameters)
    {

        $restaurant_id = $this->auth->getRestaurantID();

        return $this->restaurantRepository->read($restaurant_id);
    }

    public function CreateRestaurantAction($route, $parameters)
    {
        /**
         * All the POST data needed for Restaurant Creation
         */
        $allNecessaryPostVariables = [
            "restaurant_name",
            // "brand_id",
            "location"
        ];

        /**
         * Check if all the necessary variables in the $_POST array are present.
         * In case, they don't. Throw a insufficient data error.
         */
        foreach ($allNecessaryPostVariables as $variable) {
            if (array_key_exists($variable, $_POST) == false) {
                http_response_code(400);
                return new ReturnType(true, "INSUFFICIENT_DATA" . $variable);
            }
        }

        $restaurant_name = $_POST["restaurant_name"];

        // $brand_id = intval($_POST["brand_id"]);

        $location = $_POST["location"];

        $restaurant = new Restaurant($restaurant_name, $location);

        return $this->restaurantRepository->create($restaurant);
    }

    public function UpdateRestaurantAction($route, $parameters)
    {
        /**
         * Check if restaurant_id is present in the URL
         */
        if (array_key_exists("restaurant_id", $parameters) == false) {

            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        /**
         * Get restaurant_id from URL
         */

        $reservation_id = intval($parameters["restaurant_id"]);

        /**
         *  Get PUT data into $_PUT array
         */

        $json = file_get_contents("php://input");

        $_PUT = json_decode($json, true);

        /**
         * Name of the cols in reservation table that needs to be updated
         */

        $names = array();

        /**
         * Value of the cols in the reservation table that the col need to be updated to
         */

        $values = array();


        foreach ($_PUT as $key => $value) {
            switch ($key) {
                case "restaurant_name":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;

                case "location":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;
            }
        }

        $result = $this->restaurantRepository->update($names, $values, $reservation_id);

        return $result;
    }

    public function FetchRestaurantsAction($route, $parameters)
    {

        $admin_id = $this->auth->getID();

        return $this->allRestaurantsQuery->fetch($admin_id);
    }
}
