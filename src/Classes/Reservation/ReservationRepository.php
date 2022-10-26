<?php

declare(strict_types=1);
/**
 * Layer between Reservations Table in the DB, and Reservation Controller, Other Classes
 */
class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * Database
     *
     * @var Database
     */
    private Database $db;

    /**
     * To hold the Restaurant ID
     *
     * @var integer
     */
    private int $restaurant_id;

    /**
     * To hold the User ID
     *
     * @var integer
     */
    private int $user_id;

    /**
     * Constructor Function
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;

        $this->restaurant_id = $session->restaurant_id;

        $this->user_id = $session->user_id;
    }

    /**
     * Creates a Reservation
     *
     * @param Reservation $reservation
     * @return ReturnType
     */
    public function create(Reservation $reservation): ReturnType
    {
        $prepared_statement = 'INSERT INTO "Rezerva"."Reservation" (restaurant_id, guest_name, no_of_guests, phone, instructions, reservation_time,  status, created_by, type) VALUES (?,?,?,?,?,?,?,?,?) RETURNING *';

        $values = array($this->restaurant_id, $reservation->guest_name, $reservation->no_of_guests, $reservation->phone, $reservation->instructions, $reservation->reservation_time, $reservation->status, $this->user_id, $reservation->type);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $data = new stdClass();

        if ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->reservation = $row;

            return new ReturnType(false, "CREATE_RESERVATION_SUCCEEDED", $data);
        } else {
            return new ReturnType(true, "CREATE_RESERVATION_FAILED", $data);
        }
    }

    /**
     * Reads a Reservation
     *
     * @param integer $reservation_id
     * @return ReturnType
     */
    public function read(int $reservation_id): ReturnType
    {

        $prepared_statement = 'SELECT reservation_id, guest_name, no_of_guests, phone, instructions, status, created_by, created_at, type, reservation_time FROM "Rezerva"."Reservation" WHERE reservation_id = ? AND restaurant_id = ?';

        $values = array($reservation_id, $this->restaurant_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;


        $data = new stdClass();

        if ($PDOStatement->rowCount() == 0) {
            return new ReturnType(true, "NO_RESERVATION_FOUND");
        } elseif ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->reservation = $row;

            return new ReturnType(false, "READ_RESERVATION_SUCCEEDED", $data);
        } else {
            return new ReturnType(true, "READ_RESERVATION_FAILED", $data);
        }
    }

    /**
     * Updates a Reservation
     *
     * @param Reservation $reservation
     * @param integer $reservation_id
     * @return ReturnType
     */
    public function update(Reservation $reservation, int $reservation_id): ReturnType
    {

        $prepared_statement = 'UPDATE "Rezerva"."Reservation" SET guest_name = ?, no_of_guests = ?, phone = ?, instructions = ?, status = ?, type = ?, reservation_time = ? WHERE reservation_id = ? AND restaurant_id = ? RETURNING *';

        $values = array($reservation->guest_name, $reservation->no_of_guests, $reservation->phone, $reservation->instructions, $reservation->status, $reservation->type, $reservation->reservation_time, $reservation_id, $this->restaurant_id);

        $resultFromDBOperation = $this->db->query($prepared_statement, $values);

        $PDOStatement = $resultFromDBOperation->data->statement;

        $data = new stdClass();

        if ($resultFromDBOperation->error == false) {
            $row = $PDOStatement->fetch(PDO::FETCH_ASSOC);

            $data->reservation = $row;

            return new ReturnType(false, "UPDATE_RESERVATION_SUCCEEDED", $data);
        } else {
            return new ReturnType(true, "UPDATE_RESERVATION_FAILED", $data);
        }
    }
}