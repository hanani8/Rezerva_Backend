<?php

/**
 * @file
 * Hosts all the controller functions
 */

/**
 *
 */

// declare(strict_types=1);

// require_once "Includes/header.php";


// class ControllerClass
// {
//     private $db;

//     private $exec;

//     private $login;

//     private $reservation;

//     private $hostname = "localhost";

//     private $dbname = "Rezerva";

//     private $username = "hegemon";

//     private $password = "hanani8*";


//     public function __construct()
//     {

//         $this->db = new DatabaseClass($this->hostname, $this->dbname, $this->username, $this->password);

//         $this->login = new LoginClass($this->db);

//         $this->reservation = new ReservationClass($this->db, $this->login);
//     }

//     /*
//     TO-DO: Input sanitization and validation!
//     */

//     public function loginController($route, $parameters)
//     {

//         $userid = $_POST["userid"];

//         $username = $_POST["username"];

//         $password = $_POST["password"];

//         if (empty($userid) && empty($username)) {
//             return "BAD_REQUEST";
//         } else {
//             return $this->login->login($userid, $username, $password);
//         }
//     }

//     public function logoutController($route, $parameters)
//     {

//         if ($this->login->isLoggedIn()) {
//             return $this->login->logout();
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }

//     public function isLoggedInController($route, $parameters)
//     {
//         if ($this->login->isLoggedIn()) {
//             return "LOGGED_IN";
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }

//     public function createReservationController($route, $parameters)
//     {

//         if ($this->login->isLoggedIn()) {
//             $guest_name = $_POST["guest_name"];

//             $no_of_guests = $_POST["no_of_guests"];

//             $no_of_guests_int = intval($no_of_guests);

//             $phone = $_POST["phone"];

//             $instructions = $_POST["instructions"];

//             $date = $_POST["date"];

//             $time = $_POST["time"];

//             $reservation_time = $date . " " . $time;

//             if (empty($guest_name) || empty($no_of_guests) || empty($phone) || empty($reservation_time)) {
//                 return "BAD_REQUEST";
//             } else {
//                 return $this->reservation->create($guest_name, $no_of_guests_int, $phone, $instructions, $reservation_time);
//             }
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }

//     public function getReservationController($route, $parameters)
//     {

//         if ($this->login->isLoggedIn()) {
//             $reservation_id = $parameters["reservation_id"];

//             $reservation_id_int = intval($parameters["reservation_id"]);

//             if (empty($reservation_id)) {
//                 return "BAD_REQUEST";
//             } else {
//                 return $this->reservation->readOne($reservation_id_int);
//             }
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }

//     public function updateReservationController($route, $parameters)
//     {

//         if ($this->login->isLoggedIn()) {
//             $reservation_id = $parameters["reservation_id"];

//             $reservation_id_int = intval($reservation_id);

//             // PHP has no straight-forward $_PUT support.

//             // https://stackoverflow.com/a/41959141/11887766

//             // parse_str(file_get_contents('php://input'), $_PUT);

//             $guest_name = $_POST["guest_name"];

//             $no_of_guests = $_POST["no_of_guests"];

//             $no_of_guests_int = intval($no_of_guests);

//             $phone = $_POST["phone"];

//             $instructions = $_POST["instructions"];

//             // Create a seperate API for each status change.

//             // $status = $_POST["status"];

//             if (empty($reservation_id) || empty($guest_name) || empty($no_of_guests) || empty($phone) || empty($instructions)) {
//                 return "BAD_REQUEST";
//             } else {
//                 return $this->reservation->update($reservation_id_int, $guest_name, $no_of_guests_int, $phone, $instructions);
//             }
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }

//     public function cancelReservationController($route, $parameters)
//     {

//         if ($this->login->isLoggedIn()) {
//             $reservation_id = $parameters["reservation_id"];

//             $reservation_id_int = intval($reservation_id);

//             if (empty($reservation_id)) {
//                 return "BAD_REQUEST";
//             } else {
//                 return $this->reservation->cancel($reservation_id_int);
//             }
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }

//     public function getReservationsController($route, $parameters)
//     {

//         if ($this->login->isLoggedIn()) {
//             $offset_limit = $parameters["offset_limit"];

//             if (empty($offset_limit)) {
//                 return "BAD_REQUEST";
//             } else {
//                 preg_match('/\?offset=(\d*)&limit=(\d*)/', $offset_limit, $matches);

//                 $offset = $matches[1];

//                 $limit = $matches[2];

//                 if (!is_numeric($offset) || !is_numeric($limit)) {
//                     return "BAD_REQUEST";
//                 }

//                 $offset_int = intval($offset);

//                 $limit_int = intval($limit);

//                 return $this->reservation->readPaginated($offset_int, $limit_int);
//             }
//         } else {
//             return "NOT_LOGGED_IN";
//         }
//     }
// }
// End class.
