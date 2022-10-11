<?php

declare(strict_types=1);

require_once "Includes/header.php";

/**
 * Class that directly talks to the Database.
 */
class Database implements DatabaseInterface
{
    /**
     * Variable to hold the Connection to DB
     *
     * @var [type]
     */
    private $connection;

    /**
     * Constructor Function
     *
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        try {
            $this->connection = new PDO("pgsql:host=${host};dbname={$dbname}", $username, $password);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /**
     * Runs the Provided SQL Statement
     *
     * @param string $query
     * @param array $values
     * @return ReturnType
     */
    public function query(string $query, array $values): ReturnType
    {
        /**
         * Checking if the $connection is instance of the PDO Class
         */
        if ($this->connection instanceof PDO) {

            $stmt = $this->connection->prepare($query);

            $data = new stdClass();

            try {
                $this->connection->beginTransaction();

                $stmt->execute($values);

                $this->connection->commit();

                $data->statement = $stmt;

                return new ReturnType(false, "DATABASE_OPERATION_SUCCEEDED", $data);
            } catch (Exception $e) {
                $this->connection->rollBack();

                return new ReturnType(true, "DATABASE_OPERATION_FAILED");

                // echo "Failed: " . $e->getMessage();
            }
        }
    }

    public function __destruct()
    {
        //Disconnect from DB

        // Upon successful connection to the database, an instance of the PDO class is returned to your script. The connection remains active for the lifetime of that PDO object. To close the connection, you need to destroy the object by ensuring that all remaining references to it are deleted—you do this by assigning null to the variable that holds the object. If you don't do this explicitly, PHP will automatically close the connection when your script ends.
        // https://www.php.net/manual/en/pdo.connections.php

        $this->connection = null;
    }
}
