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
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, PATCH');

header('Access-Control-Allow-Origin: http://localhost:5173');

header('Vary: Origin');

header('Content-Type: application/json');



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
 * Creating RestaurantController Class to get Restaurant Details
 */
$restaurantController = new RestaurantController($db, $session);
/**
 * Reservation Controller, and Dashboard Controller.
 * To be instantiated only after the user if logged in, lest there will be a error in the construction functions of both the controllers.
 */
$reservationController;
$dashboardController;

/**
 * /api - GET
 */
$router->addRoute(
    '/api',
    function ($route, $parameters) {

        http_response_code(200);
        return new ReturnType(false, "API_OPERATIONAL");
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

// /api/reservations/[s:date] - GET

// $router->addRoute(
//     '/api/reservations/[s:date]',
//     function ($route, $parameters) {

//         global $reservationController;

//         $result = $reservationController->FetchWithDateReservationsAction($route, $parameters);

//         return $result;
//     },
//     'GET'
// );

// /api/reservations/[s:date]/[s:limit_offset] - GET

$router->addRoute(
    '/api/reservations/[s:date]/[s:limit_offset]',
    function ($route, $parameters) {

        global $reservationController;

        $result = $reservationController->FetchWithDateReservationsAction($route, $parameters);

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

// /api/reservation/[int:id]/edit - POST

// $router->addRoute(
//     '/api/reservation/[i:reservation_id]/edit',
//     function ($route, $parameters) {

//         global $reservationController;

//         $result = $reservationController->UpdateReservationAction($route, $parameters);

//         return $result;
//     },
//     'POST'
// );

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

// /api/reservation/[int:id] - PATCH

$router->addRoute(
    '/api/reservation/[i:reservation_id]',
    function ($route, $parameters) {


        global $reservationController;

        $result = $reservationController->newUpdateReservationAction($route, $parameters);

        return $result;

    },
    'PATCH'
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

// /api/restaurant/[int:id] - GET

$router->addRoute(
    '/api/restaurant/[i:restaurant_id]',
    function ($route, $parameters)
    {
        global $restaurantController;

        $result = $restaurantController->ReadRestaurantAction($route, $parameters);

        return $result;
    },
    'GET'
);

// /api/restaurant - GET

$router->addRoute(
    '/api/restaurant',
    function ($route, $parameters)
    {
        global $restaurantController;

        $result = $restaurantController->ReadRestaurantWithoutParameterAction($route, $parameters);

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

        $result;

        /**
         * Calling the Routes
         */
        try {
            //code...

            $result = $router->callRoute($_SERVER['REQUEST_URI']);
        } catch (\Throwable $th) {
            //throw $th;

            http_response_code(404);

            $result = new ReturnType(true, "ROUTE_NOT_FOUND");
        }

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
        if ($_SERVER['REQUEST_URI'] == '/api/login' || $_SERVER['REQUEST_URI'] == '/api/isloggedin' || $_SERVER['REQUEST_URI'] == '/api' || preg_match_all("/\/api\/restaurant\/\d*/" , $_SERVER['REQUEST_URI']) ){
            $result = $router->callRoute($_SERVER['REQUEST_URI']);

            echo json_encode($result);
        }
        /**
         * If Un-authenticated User is trying to access secure routes.
         */
        else {
            $result = new ReturnType(true, "NOT_LOGGED_IN");

            http_response_code(401);
            echo json_encode($result);
        }
    }
}
