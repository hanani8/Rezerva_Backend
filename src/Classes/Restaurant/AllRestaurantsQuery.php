<?php

declare(strict_types=1);

/**
 * Serves the same purpose as ReservationRepository, but operates on collection of reservations instead one.
 */
class AllRestaurantsQuery implements AllRestaurantsQueryInterface
{
    /**
     * Database
     *
     * @var Database
     */
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Fetches all the Restaurants of an admin
     */
    public function fetch(int $admin_id): ReturnType
    {
        /**
         * SQL Statement
         */
        $prepared_statement = 'SELECT
                                "User"."restaurant_id",
                                "restaurant_name",
                                "location",
                                "User"."username",
                                "user_id",
                                "User"."phone" 
                            FROM
                                (
                                SELECT
                                    * 
                                FROM
                                    "Rezerva"."Admin" 
                                    LEFT JOIN
                                        "Rezerva"."Restaurant" 
                                        ON "Rezerva"."Restaurant"."brand_id" = "Rezerva"."Admin"."brand_id" 
                                WHERE
                                    "admin_id" = ? 
                                )
                                as p 
                                INNER JOIN
                                "Rezerva"."User" 
                                ON "p"."restaurant_id" = "Rezerva"."User"."restaurant_id"';

        /**
         * Values to fill-in the SQL Statement
         */
        $values = array($admin_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $restaurantRows = [];

        $data = new stdClass();

        $data->restaurants = [];

        if ($resultFromDBOperation->error == false) {
            $restaurantRows = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

            $data->restaurants = $restaurantRows;

            http_response_code(200);
            return new ReturnType(false, "FETCH_RESTAURANTS_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "FETCH_RESTAURANTSS_FAILED", $data);
        }
    }
}
