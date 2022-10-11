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
        if (array_key_exists("date", $parameters)) {
            /**
             * The Date in the URL
             */
            $date = $parameters["date"];

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

                return new ReturnType(false, "READ_DASHBOARD_DATA_SUCCEEDED", $data);
            } else {
                return new ReturnType(true, "READ_DASHBOARD_DATA_FAILED", $data);
            }
        } else {
            return new ReturnType(true, "INSUFFICIENT_DATA", $data);
        }
    }
}
