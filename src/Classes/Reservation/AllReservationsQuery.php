<?php

declare(strict_types=1);

/**
 * Serves the same purpose as ReservationRepository, but operates on collection of reservations instead one.
 */
class AllReservationsQuery implements AllReservationsQueryInterface
{
    /**
     * Database
     *
     * @var Database
     */
    private Database $db;

    /**
     * Holds the Restaurant ID
     *
     * @var integer
     */
    private int $restaurant_id;

    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->restaurant_id = $session->restaurant_id;
    }

    /**
     * Fetches all the Reservations
     */
    public function fetch(): ReturnType
    {
        /**
         * SQL Statement
         */
        $prepared_statement = 'SELECT reservation_id, guest_name, no_of_guests, phone, instructions, status, created_by, created_at  FROM "Rezerva"."Reservation" WHERE restaurant_id = ? ORDER BY created_at DESC';

        /**
         * Values to fill-in the SQL Statement
         */
        $values = array($this->restaurant_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $reservationRows = [];

        $data = new stdClass();

        $data->reservations = [];

        if ($resultFromDBOperation->error == false) {
            $reservationRows = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

            $data->reservations = $reservationRows;

            http_response_code(200);
            return new ReturnType(false, "FETCH_RESERVATIONS_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "FETCH_RESERVATIONS_FAILED", $data);
        }
    }

    public function fetchWithDate(string $date): ReturnType
    {
        $datePlusOneMoreDay = new DateTime($date);

        $datePlusOneMoreDay->modify('+1 day');

        $datePlusOneMoreDayString = $datePlusOneMoreDay->format('Y-m-d');

        $prepared_statement = 'SELECT reservation_id, guest_name, no_of_guests, phone, instructions, status, created_by, created_at, reservation_time, type, "table"  FROM "Rezerva"."Reservation" WHERE restaurant_id = ? AND reservation_time >= ? AND reservation_time < ? ORDER BY created_at DESC';

        $values = array($this->restaurant_id, $date, $datePlusOneMoreDayString);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $reservationRows = [];

        $data = new stdClass();

        $data->reservations = [];

        if ($resultFromDBOperation->error == false) {

            $reservationRows = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

            $data->reservations = $reservationRows;

            http_response_code(200);
            return new ReturnType(false, "FETCH_WITH_DATE_RESERVATIONS_SUCCEEDED", $data);
        } else {
            http_response_code(500);
            return new ReturnType(true, "FETCH_WITH_DATE_RESERVATIONS_FAILED", $data);
        }
    }
}
