<?php

/**
 * File for Interface to Database Class
 * php version 8.1
 *
 * @author Jai Hanani <jaihanani8@gmail.com>
 * @link   None
 */

declare(strict_types=1);

/**
 * Interface to Database Class
 * php version 8.1
 *
 * @author Jai Hanani <jaihanani8@gmail.com>
 * @link   None
 */
interface DatabaseInterface
{
    /**
     * Constructor Function for DatabaseClass
     *
     * @param String $host     Database Host Name
     * @param String $dbname   Database Name
     * @param String $username Username
     * @param String $password Password
     *
     * @return null
     */
    public function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password
    );
    /**
     * Function that runs a SQL statement
     *
     * @param String $query  SQL Statement
     * @param Array  $values Values to fill in the SQL Statement
     *
     * @return ReturnType
     */
    public function query(string $query, array $values): ReturnType;
    /**
     * Desctructor Function for Database Class
     *
     * @return null
     */
    public function __destruct();
}
