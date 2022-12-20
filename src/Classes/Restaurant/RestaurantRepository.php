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
     * Session
     * 
     * @var Session
     * 
     */
    private Session $session;

    /**
     * Constructor Function
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
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

    /**
     * Creates a Restaurant
     *
     * @param Restaurant $restaurant
     * @return ReturnType
     */
    public function create(Restaurant $restaurant): ReturnType
    {
        $prepared_statement = 'INSERT INTO "Rezerva"."Restaurant" (restaurant_name, location, brand_id) VALUES (?,?,?) RETURNING restaurant_id';

        $values = array($restaurant->restaurant_name, $restaurant->location, $this->session->brand_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $data = new stdClass();

        if ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->restaurant_id = $row['restaurant_id'];

            http_response_code(201);
            return new ReturnType(false, "CREATE_RESTAURANT_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "CREATE_RESTAURANT_FAILED");
        }
    }

    /**
     * Updates only specific cols of a Restaurant from DB
     * 
     * @param Array $names
     * @param Array $values
     */

     public function update(array $names, array $values, int $restaurant_id): ReturnType
     {
         $prepared_statement = 'UPDATE "Rezerva"."Restaurant" SET ';
 
         foreach ($names as $name) {
             $prepared_statement .= '"' . $name . '"' . ' = ?,';
         }
 
         $prepared_statement = substr($prepared_statement, 0, -1);
 
         $prepared_statement .= ' WHERE restaurant_id = ? AND brand_id = ?';
 
         array_push($values, $restaurant_id);

         array_push($values, $this->session->brand_id);
 
         $resultFromDBOperation = $this->db->query($prepared_statement, $values);
 
         if ($resultFromDBOperation->error == false) {
             // $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);
 
             // $data->reservation = $row;
 
             http_response_code(201);
             return new ReturnType(false, "UPDATE_RESTAURANT_SUCCEEDED");
         } else {
             http_response_code(500);
             return new ReturnType(true, "UPDATE_RESTAURANT_FAILED");
         }
     }
}
