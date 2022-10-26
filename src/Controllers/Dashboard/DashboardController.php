<?php

declare(strict_types=1);

/**
 * Controller for Dashboard
 */
class DashboardController
{
    /**
     * Repository Class to Interact with the Reservations Table
     *
     * @var AllReservationsQuery
     */
    private AllReservationsQuery $allReservationsQuery;

    /**
     * Constructor Function
     *
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Database $db, Session $session)
    {
        $this->allReservationsQuery = new AllReservationsQuery($db, $session);
    }

    /**
     * Count number of walk-ins and reservations on a particular day.
     *
     * @param [type] $route
     * @param [type] $parameters
     * @return void
     */
    public function getDashboardDataAction($route, $parameters)
    {
        $data = new stdClass();

        /**
         * Checking if "date" is provided in the URL - Example: /api/reservations/"2022-10-08"
         */
        if (array_key_exists("date", $parameters) == false) {
            http_response_code(400);
            return new ReturnType(true, "DATE_NEEDED");
        }
        /**
         * The Date in the URL
         */
        $date = $parameters["date"];

        /**
         * Regex pattern for "YYYY-MM-DD" date format
         */
        $pattern = "/\d{4}-\d{2}-\d{2}/";

        if (preg_match_all($pattern, $date)) {

            /**
             * The counter for reservations
             */
            $data->reservations = 0;

            /**
             * The counter for walkins
             */
            $data->walkIns = 0;

            $result = $this->allReservationsQuery->fetchWithDate($date);

            if ($result->error == false) {

                for ($i = 0; $i < count($result->data->reservations); $i++) {
                    /**
                     * Checking for reservation. Type = 1 = Reservation
                     */
                    if ($result->data->reservations[$i]["type"] == '1') {
                        $data->reservations += 1;
                        /**
                         * Checking for walkin. Type = 2 = Walk-In
                         */
                    } elseif ($result->data->reservations[$i]["type"] == '2') {
                        $data->walkIns += 1;
                    }
                }

                http_response_code(200);
                return new ReturnType(false, "READ_DASHBOARD_DATA_SUCCEEDED", $data);
            } else {
                http_response_code(500);
                return new ReturnType(true, "READ_DASHBOARD_DATA_FAILED", $data);
            }
        } else {
            http_response_code(400);
            return new ReturnType(true, "WRONG_DATE_FORMAT");
        }
    }
}
