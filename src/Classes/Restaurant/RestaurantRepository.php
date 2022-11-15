<?php

declare(strict_types=1);
/**
 * Layer between Reservations Table in the DB, and Reservation Controller, Other Classes
 */
class RestaurantRepository implements RestaurantRepositoryInterface
{
    /**
     * Database
     *
     * @var Database
     */
    private Database $db;

    /**
     * Constructor Function
     */
    public function __construct(Database $db)
    {
        $this->db = $db;

    }

    /**
     * Reads a Reservation
     *
     * @param integer $restaurant_id
     * @return ReturnType
     */
    public function read(int $restaurant_id): ReturnType
    {

        $prepared_statement = 'SELECT restaurant_id, restaurant_name, location FROM "Rezerva"."Restaurant" WHERE restaurant_id = ?';

        $values = array($restaurant_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;


        $data = new stdClass();

        if ($PDOStatement->rowCount() == 0) {
            http_response_code(404);
            return new ReturnType(true, "NO_RESTAURANT_FOUND");
        } elseif ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->restaurant = $row;

            http_response_code(200);
            return new ReturnType(false, "READ_RESTAURANT_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "READ_RESTAURANT_FAILED", $data);
        }
    }
}
