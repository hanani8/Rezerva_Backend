<?php

declare(strict_types=1);

// set the default timezone to use.

date_default_timezone_set('Asia/Kolkata');

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
            "type",
            "table"
        ];

        /**
         * Check if all the necessary variables in the $_POST array are present.
         * In case, they don't. Throw a insufficient data error.
         */
        foreach ($allNecessaryPostVariables as $variable) {
            if (array_key_exists($variable, $_POST) == false) {
                http_response_code(400);
                return new ReturnType(true, "INSUFFICIENT_DATA");
            }
        }
        $guest_name = $_POST["guest_name"];

        $no_of_guests = intval($_POST["no_of_guests"]);

        $phone = $_POST["phone"];

        $instructions = $_POST["instructions"];

        $date = $_POST["date"];

        $date_pattern = "/\d{4}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}/";

        if (preg_match_all($date_pattern, $date) == false) {
            http_response_code(400);
            return new ReturnType(true, "WRONG_DATE_FORMAT");
        }

        $time = $_POST["time"];

        $time_pattern = "/[0-2]{1}[0-9]{1}:[0-9]{1}[0-9]{1}/";

        if (preg_match_all($time_pattern, $time) == false) {
            http_response_code(400);
            return new ReturnType(true, "WRONG_TIME_FORMAT");
        }

        $reservation_time = $date . " " . $time;

        /**
         * Today's Date
         */

        $todaysDate = new DateTimeImmutable();

        /**
         * Today's Date in YYYY-MM-DD HH:MM format
         */

        $todaysDateFormatted = $todaysDate->format('o-m-d G:i');

        /**
         * Confirming that the new reservation is not for past time.
         */

        if ($reservation_time < $todaysDateFormatted) {

            http_response_code(400);
            return new ReturnType(true, "CANNOT_CREATE_RESERVATION_FOR_PAST_TIME");
        }

        $status = intval($_POST["status"]);

        // Need validation for reservation type. Allowed values: [1, 2]

        $type = intval($_POST["type"]);

        /**
         * Table No
         */

        $table = $_POST["table"];


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
            $type,
            $table
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
        if (array_key_exists("reservation_id", $parameters) == false) {
            http_response_code(400);
            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        $reservation_id = intval($parameters["reservation_id"]);

        $result = $this->reservationRepository->read($reservation_id);

        return $result;
    }

    // public function UpdateReservationAction($route, $parameters)
    // {
    //     /**
    //      * Check if reservation_id is present in the URL
    //      */
    //     if (array_key_exists("reservation_id", $parameters) == false) {

    //         return new ReturnType(true, "INSUFFICIENT_DATA");
    //     }

    //     /**
    //      * All the necessary data for successful reservation updation
    //      */
    //     $allNecessaryUpdateVariables = [
    //         "guest_name",
    //         "no_of_guests",
    //         "phone",
    //         "instructions",
    //         "date",
    //         "time",
    //         "status",
    //         "type"
    //     ];

    //     /**
    //      * Check if all the necessary variables are present in the $_POST array
    //      */
    //     foreach ($allNecessaryUpdateVariables as $variable) {
    //         if (array_key_exists($variable, $_POST) == false) {
    //             http_response_code(400);
    //             return new ReturnType(true, "INSUFFICIENT_DATA");
    //         }
    //     }

    //     $reservation_id = intval($parameters["reservation_id"]);

    //     // PHP has no straight-forward $_PUT support.

    //     // https://stackoverflow.com/a/41959141/11887766

    //     // parse_str(file_get_contents('php://input'), $_PUT);

    //     $guest_name = $_POST["guest_name"];

    //     $no_of_guests = intval($_POST["no_of_guests"]);

    //     $phone = $_POST["phone"];

    //     $instructions = $_POST["instructions"];

    //     $date = $_POST["date"];

    //     $date_pattern = "/\d{4}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}/";

    //     if (preg_match_all($date_pattern, $date) == false) {
    //         http_response_code(400);
    //         return new ReturnType(true, "WRONG_DATE_FORMAT");
    //     }

    //     $time = $_POST["time"];

    //     $time_pattern = "/[0-2]{1}[0-9]{1}:[0-9]{1}[0-9]{1}/";

    //     if (preg_match_all($time_pattern, $time) == false) {
    //         http_response_code(400);
    //         return new ReturnType(true, "WRONG_TIME_FORMAT");
    //     }

    //     $reservation_time = $date . " " . $time;

    //     $read_reservation = $this->reservationRepository->read($reservation_id);

    //     if ($read_reservation->error == true) {
    //         return $read_reservation;
    //     }

    //     if ($read_reservation->data->reservation["status"] == 2) {
    //         http_response_code(400);
    //         return new ReturnType(true, "CANNOT_UPDATE_ALREADY_CANCELLED_RESERVATION");
    //     }

    //     $todaysDate = new DateTimeImmutable();

    //     $todaysDateFormatted = $todaysDate->format('o-m-d G:i');

    //     if ($read_reservation->data->reservation['reservation_time'] < $todaysDateFormatted) {
    //         http_response_code(400);
    //         return new ReturnType(true, "CANNOT_UPDATE_PAST_RESERVATION");
    //     }

    //     if ($reservation_time < $todaysDateFormatted) {
    //         http_response_code(400);
    //         return new ReturnType(true, "CANNOT_UPDATE_RESERVATION_TO_PAST_TIME");
    //     }

    //     $status = intval($_POST["status"]);

    //     $type = intval($_POST["type"]);

    //     // Create a seperate API for each status change.

    //     $reservation = new Reservation(
    //         $guest_name,
    //         $no_of_guests,
    //         $phone,
    //         $instructions,
    //         $reservation_time,
    //         $status,
    //         $type
    //     );

    //     $result = $this->reservationRepository->update($reservation, $reservation_id);

    //     return $result;
    // }

    public function CancelReservationAction($route, $parameters)
    {
        /**
         * Checking if reservation_id is present in the URL
         */
        if (array_key_exists("reservation_id", $parameters) == false) {
            http_response_code(400);
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
        if ($read_reservation->error == true) {
            return $read_reservation;
        } else {
            $read_reservation->data->reservation["status"] = 2;

            /**
             * From the data from previous reservation_read, create a new reservation object.
             */
            $reservation = new Reservation(
                ...$read_reservation->data->reservation
            );

            $todaysDate = new DateTimeImmutable();

            $todaysDateFormatted = $todaysDate->format('o-m-d G:i');

            if ($read_reservation->data->reservation['reservation_time'] < $todaysDateFormatted) {
                http_response_code(400);
                return new ReturnType(true, "CANNOT_CANCEL_PAST_RESERVATION");
            }

            /**
             * Update the reservation
             */
            $result = $this->reservationRepository->update($reservation, $reservation_id);

            if ($result->error == false) {
                http_response_code(201);
                return new ReturnType(false, "CANCEL_RESERVATION_SUCCEEDED");
            } else {
                return $result;
            }
        }
    }

    public function newUpdateReservationAction($route, $parameters)
    {
        /**
         * Check if reservation_id is present in the URL
         */
        if (array_key_exists("reservation_id", $parameters) == false) {

            return new ReturnType(true, "INSUFFICIENT_DATA");
        }

        /**
         * Get Reservation ID from URL
         */

        $reservation_id = intval($parameters["reservation_id"]);

        /**
         *  Get PUT data into $_PUT array
         */

        $json = file_get_contents("php://input");

        $_PUT = json_decode($json, true);

        /**
         * Name of the cols in reservation table that needs to be updated
         */

        $names = array();

        /**
         * Value of the cols in the reservation table that the col need to be updated to
         */

        $values = array();


        /**
         * Read the reservation
         */

        $read_reservation = $this->reservationRepository->read($reservation_id);

        if ($read_reservation->error == true) {
            return $read_reservation;
        }

        /**
         * If the reservation is already cancelled, cannot update it.
         */

        if ($read_reservation->data->reservation["status"] == 2) {
            http_response_code(400);
            return new ReturnType(true, "CANNOT_UPDATE_ALREADY_CANCELLED_RESERVATION");
        }

        $todaysDate = new DateTimeImmutable();

        $todaysDateFormatted = $todaysDate->format('o-m-d G:i');

        /**
         * Cannot update a past reservation
         */

        if ($read_reservation->data->reservation['reservation_time'] < $todaysDateFormatted) {
            http_response_code(400);
            return new ReturnType(true, "CANNOT_UPDATE_PAST_RESERVATION");
        }

        foreach ($_PUT as $key => $value) {
            switch ($key) {
                case "guest_name":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;

                case "no_of_guests":
                    array_push($names, $key);
                    $no_of_guests = intval($value);
                    array_push($values, $no_of_guests);
                    break;

                case "phone":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;

                case "instructions":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;

                case "reservation_time":
                    $reservation_time = $value;

                    if ($this->reservationTimeValidation($reservation_time) == true) {

                        /**
                         * Cannot update the reservation to past time
                         */

                        if ($_PUT["reservation_time"] < $todaysDateFormatted) {
                            http_response_code(400);
                            return new ReturnType(true, "CANNOT_UPDATE_RESERVATION_TO_PAST_TIME");
                        }

                        array_push($names, $key);

                        array_push($values, $value);

                        break;
                    } else {
                        http_response_code(400);
                        return new ReturnType(true, "WRONG_RESERVATION_TIME_FORMAT");
                    }

                case "status":
                    $names = array();
                    $values = array();
                    array_push($names, $key);
                    $status = intval($value);
                    array_push($values, 2);
                    break 2;

                case "type":
                    array_push($names, $key);
                    $reservation_type = intval($value);
                    array_push($values, $reservation_type);
                    break;

                case "table":
                    array_push($names, $key);
                    array_push($values, $value);
                    break;
            }
        }

        $result = $this->reservationRepository->newUpdate($names, $values, $reservation_id);

        return $result;
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
        if (array_key_exists("date", $parameters) == false) {
            http_response_code(400);
            return new ReturnType(true, "DATE_NEEDED");
        }

        $limit_offset_present = false;

        $limit_offset = "";

        /**
         * Check if Offset and Limit is present in the URL
         */
        if (array_key_exists("limit_offset", $parameters) == true) {

            $limit_offset_present = true;

            $limit_offset = $parameters["limit_offset"];
        }

        $date = $parameters["date"];


        $pattern = "/\d{4}-\d{2}-\d{2}/";

        $limit_offset_pattern = "/\?limit=\d*&offset=\d*/";

        // return $limit_offset;

        if ($limit_offset_present && preg_match_all($limit_offset_pattern, $limit_offset) == false) {
            http_response_code(400);
            return new ReturnType(true, "WRONG_LIMIT_OFFSET_PATTERN");
        }

        $limit_offset_without_query_symbol = substr($limit_offset, 1);

        parse_str($limit_offset_without_query_symbol, $limit_offset_parsed_string);

        $limit = intval($limit_offset_parsed_string["limit"]);

        $offset = intval($limit_offset_parsed_string["offset"]);

        if (preg_match_all($pattern, $date)) {
            $result = $this->allReservationsQuery->fetchWithDateLimitOffset($date, $limit, $offset);

            return $result;
        } else {
            http_response_code(400);
            return new ReturnType(true, "WRONG_DATE_FORMAT");
        }
    }

    private function reservationTimeValidation(string $reservation_time)
    {
        $pattern = "/\d{4}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1} [0-2]{1}[0-9]{1}:[0-9]{1}[0-9]{1}/";

        if (preg_match_all($pattern, $reservation_time) == false) {
            return false;
        }

        return true;
    }
}
