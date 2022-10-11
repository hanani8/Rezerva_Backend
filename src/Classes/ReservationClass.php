<?php

declare(strict_types=1);

require_once "Includes/autoloader.php";

class ReservationClass implements ReservationInterface
{
    private $executor;

    private $user;

    public function __construct($exec, $user)
    {

        $this->executor = $exec;

        $this->user = $user;
    }

    public function create(string $guest_name, int $no_of_guests, string $phone, string $instructions, string $reservation_time)
    {

        $prepared_statement = 'INSERT INTO "Rezerva"."Reservation" (restaurant_id, guest_name, no_of_guests, phone, instructions, reservation_time,  status, created_by) VALUES (?,?,?,?,?,?,?,?) RETURNING *';

        $values = array($this->user->getRestaurantID(), $guest_name, $no_of_guests, $phone, $instructions, $reservation_time, 1, $this->user->getUserID());

        $stmt = $this->executor->query($prepared_statement, $values);


        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } else {
            return "CREATE_RESERVATION_FAILED";
        }
    }

    public function cancel(int $reservation_id)
    {

        $prepared_statement = 'UPDATE "Rezerva"."Reservation" SET status = 2 WHERE reservation_id = ? RETURNING reservation_id, guest_name, no_of_guests, phone, instructions, status, created_by, created_at';

        $values = array($reservation_id);

        $stmt = $this->executor->query($prepared_statement, $values);

        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } else {
            return "CANCEL_RESERVATION_FAILED";
        }
    }

    private function isNotCancelled(int $reservation_id)
    {

        $prepared_statement = 'SELECT status from "Rezerva"."Reservation" WHERE reservation_id = ?';

        $values = array($reservation_id);

        $stmt = $this->executor->query($prepared_statement, $values);

        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($data[0]["status"] != 2) {
                return true;
            } else {
                return false;
            };
        } else {
            return "IS_CANCELLED_RESERVATION_FAILED";
        }
    }

    public function update(int $reservation_id, string $guest_name, int $no_of_guests, string $phone, string $instructions)
    {

        $prepared_statement = 'UPDATE "Rezerva"."Reservation" SET guest_name = ?, no_of_guests = ?, phone = ?, instructions = ? WHERE reservation_id = ? RETURNING *';

        $values = array($guest_name, $no_of_guests, $phone, $instructions, $reservation_id);

        $stmt = $this->executor->query($prepared_statement, $values);

        if ($stmt && $this->isNotCancelled($reservation_id) === true) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } else {
            return "UPDATE_RESERVATION_FAILED";
        }
    }


    public function readPaginated(int $offset, int $limit)
    {

        $prepared_statement = 'SELECT reservation_id, guest_name, no_of_guests, phone, instructions, status, created_by, created_at  FROM "Rezerva"."Reservation" WHERE restaurant_id = ? ORDER BY created_at DESC OFFSET ? LIMIT ?';

        $values = array($this->user->getRestaurantID(), $offset, $limit);

        $stmt = $this->executor->query($prepared_statement, $values);

        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } else {
            return "READ_PAGINATED_RESERVATIONS_FAILED";
        }
    }

    public function readOne(int $reservation_id)
    {

        $prepared_statement = 'SELECT reservation_id, guest_name, no_of_guests, phone, instructions, status, created_by, created_at, reservation_time FROM "Rezerva"."Reservation" WHERE reservation_id = ?';

        $values = array($reservation_id);

        $stmt = $this->executor->query($prepared_statement, $values);

        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } else {
            return "READ_RESERVATION_FAILED";
        }
    }
}
