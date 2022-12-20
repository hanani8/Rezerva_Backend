<?php

declare(strict_types=1);
/**
 * Layer between Reservations Table in the DB, and Reservation Controller, Other Classes
 */
class BrandRepository implements BrandRepositoryInterface
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
     * Creates a Brand
     *
     * @param Brand $brand
     * @return ReturnType
     */
    public function create(Brand $brand): ReturnType
    {
        $prepared_statement = 'INSERT INTO "Rezerva"."Brand" (brand_name) VALUES (?) RETURNING brand_id';

        $values = array($brand->brand_name);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $data = new stdClass();

        if ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->brand_id = $row['brand_id'];

            http_response_code(201);
            return new ReturnType(false, "CREATE_BRAND_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "CREATE_BRAND_FAILED");
        }
    }

    /**
     * Reads a Brand
     *
     * @param integer $brand_id
     * @return ReturnType
     */
    public function read(int $brand_id): ReturnType
    {

        $prepared_statement = 'SELECT brand_id, brand_name FROM "Rezerva"."Brand" WHERE brand_id = ?';

        $values = array($brand_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;


        $data = new stdClass();

        if ($PDOStatement->rowCount() == 0) {
            http_response_code(404);
            return new ReturnType(true, "NO_BRAND_FOUND");
        } elseif ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->brand = $row;

            http_response_code(200);
            return new ReturnType(false, "READ_BRAND_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "READ_BRAND_FAILED", $data);
        }
    }

        /**
     * Updates only specific cols of a Brand from DB
     * 
     * @param Array $names
     * @param Array $values
     */

     public function update(array $names, array $values, int $brand_id): ReturnType
     {
         $prepared_statement = 'UPDATE "Rezerva"."Brand" SET ';
 
         foreach ($names as $name) {
             $prepared_statement .= '"' . $name . '"' . ' = ?,';
         }
 
         $prepared_statement = substr($prepared_statement, 0, -1);
 
         $prepared_statement .= ' WHERE brand_id = ?';
 
         array_push($values, $brand_id);

         $resultFromDBOperation = $this->db->query($prepared_statement, $values);
 
         if ($resultFromDBOperation->error == false) {
             // $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);
 
             // $data->reservation = $row;
 
             http_response_code(201);
             return new ReturnType(false, "UPDATE_BRAND_SUCCEEDED");
         } else {
             http_response_code(500);
             return new ReturnType(true, "UPDATE_BRAND_FAILED");
         }
     }
}
