<?php

declare(strict_types=1);

/**
 * Serves the same purpose as BrandRepository, but operates on collection of brands instead of one.
 */
class AllBrandsQuery implements AllBrandsQueryInterface
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
     * Fetches all the Brands
     */
    public function fetch(): ReturnType
    {
        /**
         * SQL Statement
         */
        $prepared_statement = '
        SELECT "Brand"."brand_id",
        "brand_name",
        "username",
        "password",
        "phone",
        "admin_id"
        FROM "Rezerva"."Brand"
        LEFT JOIN "Rezerva"."Admin" ON "Rezerva"."Brand"."brand_id" = "Rezerva"."Admin"."brand_id"
        ORDER BY "Brand"."brand_id"
 ';

        $values = array();

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $brandRows = [];

        $data = new stdClass();

        $data->brands = [];

        if ($resultFromDBOperation->error == false) {
            $brandRows = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

            $data->brands = $brandRows;

            http_response_code(200);
            return new ReturnType(false, "FETCH_BRANDS_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "FETCH_BRANDS_FAILED", $data);
        }
    }
}
