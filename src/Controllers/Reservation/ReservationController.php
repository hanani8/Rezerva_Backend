<?php

declare(strict_types=1);
/**
 * Controller for all endpoints related to Reservation
 */
class ReservationController extends UserController
{
    private ReservationRepository $reservationRepository;

    private AllReservationsQuery $allReservationsQuery;

    /**
     * Constructor Function
     *
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Database $db, Session $session)
    {
        $this->reservationRepository = new ReservationRepository($db, $session);

        $this->allReservationsQuery = new AllReservationsQuery($db, $session);
    }

    /**
     * Method for creating Reservation.
     *
     * @param [type] $route
     * @param [type] $parameters
     * @return void
     */
    public function CreateReservationAction($route, $parameters)
    {
        /**
         * All the POST data needed for Reservation Creation
         */
        $allNecessaryPostVariables = [
            "guest_name",
            "no_of_guests",
            "phone",
            "instructions",
            "date",
            "time",
            "status",
            "type"
        ];

        /**
         * Check if all the necessary variables in the $_POST array are present.
         * In case, they don't. Throw a insufficient data error.
         */
        foreach($allNecessaryPostVariables as $variable)
        {
            if(array_key_exists($variable, $_POST) == false)
            {
                return new ReturnType(true, "INSUFFICIENT_DATA");
            }
        }
        $guest_name = $_POST["guest_name"];

        $no_of_guests = intval($_POST["no_of_guests"]);

        $phone = $_POST["phone"];

        $instructions = $_POST["instructions"];

        $date = $_POST["date"];

        $time = $_POST["time"];

        $reservation_time = $date . " " . $time;

        $status = intval($_POST["status"]);

        $type = intval($_POST["type"]);

        /**
         * Creating new Reservation Object
         */
        $reservation = new Reservation(
            $guest_name,
            $no_of_guests,
            $phone,
            $instructions,
            $reservation_time,
            $status,
            $type
        );

        /**
         * Reservation Creation
         */
        $result = $this->reservationRepository->create($reservation);

        return $result;
    }

    public function ReadReservationAction($route, $parameters)
    {
        /**
         * Check if reservation_id is present in the URL - Ex: api/reservation/12
         */
        if(array_key_exists("reservation_id", $parameters) == false)
        {
            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        $reservation_id = intval($parameters["reservation_id"]);

        $result = $this->reservationRepository->read($reservation_id);

        return $result;
    }

    public function UpdateReservationAction($route, $parameters)
    {
        /**
         * Check if reservation_id is present in the URL
         */
        if(array_key_exists("reservation_id", $parameters) == false)
        {
            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        /**
         * All the necessary data for successful reservation updation
         */
        $allNecessaryUpdateVariables = [
            "guest_name",
            "no_of_guests",
            "phone",
            "instructions",
            "date",
            "time",
            "status",
            "type"
        ];

        /**
         * Check if all the necessary variables are present in the $_POST array
         */
        foreach($allNecessaryUpdateVariables as $variable)
        {
            if(array_key_exists($variable, $_POST) == false)
            {
                return new ReturnType(true, "INSUFFICIENT_DATA");
            }
        }

        $reservation_id = intval($parameters["reservation_id"]);

        // PHP has no straight-forward $_PUT support.

        // https://stackoverflow.com/a/41959141/11887766

        // parse_str(file_get_contents('php://input'), $_PUT);

        $guest_name = $_POST["guest_name"];

        $no_of_guests = intval($_POST["no_of_guests"]);

        $phone = $_POST["phone"];

        $instructions = $_POST["instructions"];

        $date = $_POST["date"];

        $time = $_POST["time"];

        $reservation_time = $date . " " . $time;

        $status = intval($_POST["status"]);

        $type = intval($_POST["type"]);

        // Create a seperate API for each status change.

        $reservation = new Reservation(
            $guest_name,
            $no_of_guests,
            $phone,
            $instructions,
            $reservation_time,
            $status,
            $type
        );

        $result = $this->reservationRepository->update($reservation, $reservation_id);

        return $result;
    }

    public function CancelReservationAction($route, $parameters)
    {
        /**
         * Checking if reservation_id is present in the URL
         */
        if(array_key_exists("reservation_id", $parameters) == false)
        {
            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        $reservation_id = intval($parameters["reservation_id"]);

        /**
         * Read the Reservation
         */
        $read_reservation = $this->reservationRepository->read($reservation_id);

        /**
         * Check for Errors
         */
        if($read_reservation->error == true)
        {
            return $read_reservation;
        }
        else
        {
            /**
             * Check if the reservation is already cancelled
             */
            if ($read_reservation->data->reservation["status"] != 2) {
                $read_reservation->data->reservation["status"] = 2;

                /**
                 * From the data from previous reservation_read, create a new reservation object.
                 */
                $reservation = new Reservation(
                    ...$read_reservation->data->reservation
                );
    
                /**
                 * Update the reservation
                 */
                $result = $this->reservationRepository->update($reservation, $reservation_id);
    
                if ($result->message == "UPDATE_RESERVATION_SUCCEEDED") {
                    return new ReturnType(false, "CANCEL_RESERVATION_SUCCEEDED");
                } else {
                    return new ReturnType(true, "CANCEL_RESERVATION_FAILED");
                }
            } else {
                return new ReturnType(true, "CANCEL_RESERVATION_FAILED");
            }
        }
    }

    public function FetchReservationsAction($route, $parameters)
    {
        $result = $this->allReservationsQuery->fetch();

        return $result;
    }

    public function FetchWithDateReservationsAction($route, $parameters)
    {
        /**
         * Check if the "date" is present in the URL
         */
        if(array_key_exists("date", $parameters) == false)
        {
            return new ReturnType(true, "DATE_NEEDED");
        }

        $date = $parameters["date"];

        $result = $this->allReservationsQuery->fetchWithDate($date);

        return $result;
    }
}
