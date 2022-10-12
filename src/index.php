<?php

/**
 * @file
 *
 * Host of Mezon Router
 */

/**
 * Include the Header file for autoloading classes
 */
require_once "Includes/header.php";

/**
 * Allow Credential-ed requests
 */
header('Access-Control-Allow-Credentials:true');

/**
 * Allow Requests from Specific Origins
 */
header('Access-Control-Allow-Origin: http://localhost:5173');

header('Vary: Origin');

header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');


// Routes
/**
 * /api/login
 * /api/isloggedin
 * /api/logout
 * /api/reservations/$date
 * /api/reservation/$id
 * /api/reservation/$id/edit
 * /api/reservation/$id/cancel
 * /api/dashboard/$date
 */

/**
 * Creating the Router Object
 */
$router = new \Mezon\Router\Router();

/**
 * Credentials for accessing DB
 */
$host = "localhost";
$dbname = "Rezerva";
$username = "hegemon";
$password = "hanani8*";

/**
 * Creating DB Class
 */
$db = new Database($host, $dbname, $username, $password);

/**
 * Creating Session Class
 */
$session = new Session();

/**
 * Creating UserController Class to handle authentication
 */
$userController = new UserController($db, $session);
/**
 * Reservation Controller, and Dashboard Controller.
 * To be instantiated only after the user if logged in, lest there will be a error in the construction functions of both the controllers.
 */
$reservationController;
$dashboardController;

/**
 * / - GET
 */
$router->addRoute(
    '/',
    function ($route, $parameters) {

        return $_SERVER['HTTP_ORIGIN'];
    },
    'GET'
);
/**
 * /api/login - POST
 */
$router->addRoute(
    '/api/login/',
    function ($route, $parameters) {

        global $userController;

        $result = $userController->loginAction($route, $parameters);

        return $result;
    },
    'POST'
);

// /api/logout - GET

$router->addRoute(
    '/api/logout/',
    function ($route, $parameters) {
        global $userController;

        $result = $userController->logoutAction($route, $parameters);

        return $result;
    },
    'GET'
);

// /api/isloggedin - GET

$router->addRoute(
    '/api/isloggedin',
    function ($route, $parameters) {

        global $userController;

        $result = $userController->isLoggedInAction($route, $parameters);

        return $result;
    },
    'GET'
);



// /api/reservation - POST

$router->addRoute(
    '/api/reservation',
    function ($route, $parameters) {
        global $reservationController;

        $result = $reservationController->CreateReservationAction($route, $parameters);

        return $result;
    },
    'POST'
);


// /api/reservation/[int:id] - GET

$router->addRoute(
    '/api/reservation/[i:reservation_id]',
    function ($route, $parameters) {

        global $reservationController;

        $result = $reservationController->ReadReservationAction($route, $parameters);

        return $result;
    },
    'GET'
);

// /api/reservation/[int:id]/edit - POST

$router->addRoute(
    '/api/reservation/[i:reservation_id]/edit',
    function ($route, $parameters) {

        global $reservationController;

        $result = $reservationController->UpdateReservationAction($route, $parameters);

        return $result;
    },
    'POST'
);

// /api/reservation/[int:id]/cancel - GET

$router->addRoute(
    '/api/reservation/[i:reservation_id]/cancel',
    function ($route, $parameters) {


        global $reservationController;

        $result = $reservationController->CancelReservationAction($route, $parameters);

        return $result;
    },
    'GET'
);


$router->addRoute(
    '/api/reservations',
    function ($route, $parameters) {

        global $reservationController;

        $result = $reservationController->FetchReservationsAction($route, $parameters);

        return $result;
    },
    'GET'
);

// /api/reservation/[s:date] - GET

$router->addRoute(
    '/api/reservations/[s:date]',
    function ($route, $parameters) {

        global $reservationController;

        $result = $reservationController->FetchWithDateReservationsAction($route, $parameters);

        return $result;
    },
    'GET'
);



// /api/profile

$router->addRoute(
    '/api/profile',
    function ($route, $parameters) {

        global $userController;

        $result = $userController->getProfileAction($route, $parameters);

        return $result;
    },
    'GET'
);

// /api/dashboard/[s:date] - GET

$router->addRoute(
    '/api/dashboard/[s:date]',
    function ($route, $parameters) {
        global $dashboardController;

        $result = $dashboardController->getDashboardDataAction($route, $parameters);

        return $result;
    },
    'GET'
);

/**
 * Non-trivial HTTP requests like POST, PUT, etc... auto-triggers a preflight-request of OPTIONS type.
 * Handling it here.
 */
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    echo "Allow";
} else {
    /**
     * Check if User is Logged In
     */
    if ($session->is_logged_in()) {
        /**
         * Instantiate the Reservation Controller, and Dashboard Controller
         */
        $reservationController = new ReservationController($db, $session);
        $dashboardController = new DashboardController($db, $session);

        /**
         * Calling the Routes
         */
        $result = $router->callRoute($_SERVER['REQUEST_URI']);

        /**
         * JSON Encoding
         */
        echo json_encode($result);
    } 
    /**
     * If the User is not logged in
     */
    else {

        /**
         * Only accept /login, /isloggedin requests when the user is not logged in, since the user needs these routes to authenticate himself.
         */
        if ($_SERVER['REQUEST_URI'] == '/api/login' || $_SERVER['REQUEST_URI'] == '/api/isloggedin' || $_SERVER['REQUEST_URI'] == '/') {
            $result = $router->callRoute($_SERVER['REQUEST_URI']);

            echo json_encode($result);
        } 
        /**
         * If Un-authenticated User is trying to access secure routes.
         */
        else {
            $result = new ReturnType(false, "NOT_LOGGED_IN");

            echo json_encode($result);
        }
    }
}
