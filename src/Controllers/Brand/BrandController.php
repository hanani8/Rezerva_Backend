<?php

/**
 * The BrandRepository Class is for interacting with the Restaurant Table in DB
 */

declare(strict_types=1);

/**
 * Controller for all api endpoints related to Brand
 */
class BrandController
{
    private BrandRepository $brandRepository; // To read a brand, create a brand

    private AllBrandsQuery $allBrandsQuery; // To read all brands

    private Auth $auth;



    /**
     * Constructor Function
     *
     * @param [type] $db
     * @param [type] $session
     */
    public function __construct($db, $session)
    {
        $this->brandRepository = new BrandRepository($db);

        $this->allBrandsQuery = new AllBrandsQuery($db);

        $this->auth = new Auth($session);
    }

    public function CreateBrandAction($route, $parameters)
    {

        $brand_name = $_POST["brand_name"];

        $brand = new Brand($brand_name);

        return $this->brandRepository->create($brand);
    }

    public function ReadBrandAction($route, $parameters) 
    {

        $brand_id = intval($parameters["brand_id"]);

        return $this->brandRepository->read($brand_id);
    }

    public function UpdateBrandAction($route, $parameters)
    {
                /**
         * Check if restaurant_id is present in the URL
         */
        if (array_key_exists("brand_id", $parameters) == false) {

            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        /**
         * Get restaurant_id from URL
         */

        $brand_id = intval($parameters["brand_id"]);

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
                case "brand_name":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;

            }
        }

        $result = $this->brandRepository->update($names, $values, $brand_id);

        return $result;
    }

    public function FetchBrandsAction($route, $parameters)
    {

        return $this->allBrandsQuery->fetch();
    }
}
