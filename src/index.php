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
header("Access-Control-Allow-Credentials:true");

/**
 * Allow Requests from Specific Origins
 */
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, PATCH");

header("Access-Control-Allow-Origin: http://localhost:5173");

header("Vary: Origin");

header("Content-Type: application/json");

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
 * Creating BrandController class to get Brand(s) Details
 */
$brandController = new BrandController($db, $session);
/**
 * Creating AdminController class to get/create Admin(s)
 */
$adminController = new AdminController($db, $session);
/**
 * Creating superAdminController class to login superAdmin
 */
$superAdminController = new SuperadminController($db, $session);
/**
 * Reservation Controller, and Dashboard Controller.
 */
$reservationController = new ReservationController($db, $session);
$dashboardController = new DashboardController($db, $session);

/**
 * /api - GET
 */
$router->addRoute(
    "/api",
    function ($route, $parameters) {
        http_response_code(200);
        return new ReturnType(false, "API_OPERATIONAL");
    },
    "GET"
);
/**
 * /api/login - POST
 */
$router->addRoute(
    "/api/login/",
    function ( $parameters) {
        global $userController;

        $route = null;

        $result = $userController->loginAction($route, $parameters);

        return $result;
    },
    "POST"
);

// /api/logout - GET

$router->addRoute(
    "/api/logout/",
    function ($route, $parameters) {
        global $userController;

        $result = $userController->logoutAction($route, $parameters);

        return $result;
    },
    "GET"
);

// /api/isloggedin - GET

$router->addRoute(
    "/api/isloggedin",
    function ($route, $parameters) {
        global $userController;

        $result = $userController->isLoggedInAction($route, $parameters);

        return $result;
    },
    "GET"
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
    "/api/reservations/[s:date]/[s:limit_offset]",
    function ($route, $parameters) {
        global $reservationController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $reservationController->FetchWithDateReservationsAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/reservation - POST

$router->addRoute(
    "/api/reservation",
    function ($route, $parameters) {
        global $reservationController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $reservationController->CreateReservationAction(
            $route,
            $parameters
        );

        return $result;
    },
    "POST"
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
    "/api/reservation/[i:reservation_id]/cancel",
    function ($route, $parameters) {
        global $reservationController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $reservationController->CancelReservationAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/reservation/[int:id] - PATCH

$router->addRoute(
    "/api/reservation/[i:reservation_id]",
    function ($route, $parameters) {
        global $reservationController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $reservationController->newUpdateReservationAction(
            $route,
            $parameters
        );

        return $result;
    },
    "PATCH"
);

// /api/profile

$router->addRoute(
    "/api/profile",
    function ($route, $parameters) {
        global $userController;

        $result = $userController->getProfileAction($route, $parameters);

        return $result;
    },
    "GET"
);

// /api/reservation/[int:id] - GET

$router->addRoute(
    "/api/reservation/[i:reservation_id]",
    function ($route, $parameters) {
        global $reservationController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $reservationController->ReadReservationAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/restaurant/[int:id] - GET

$router->addRoute(
    "/api/restaurant/[i:restaurant_id]",
    function ($route, $parameters) {
        global $restaurantController;
        

        $result = $restaurantController->ReadRestaurantAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/restaurant/[int:id] - PATCH

$router -> addRoute(
    "/api/restaurant/[i:restaurant_id]",
    function ($route, $parameters) {
        global $restaurantController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "ADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_ADMIN");
        }

        $result = $restaurantController->UpdateRestaurantAction(
            $route,
            $parameters
        );

        return $result;
    },
    "PATCH"
);

// /api/restaurant - GET

$router->addRoute(
    "/api/restaurant",
    function ($route, $parameters) {
        global $restaurantController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $restaurantController->ReadRestaurantWithoutParameterAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/brand/[int:id] - GET

$router->addRoute(
    "/api/brand/[i:brand_id]",
    function ($route, $parameters) {
        global $brandController;
        

        $result = $brandController->ReadBrandAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/brand/[int:id] - PATCH

$router -> addRoute(
    "/api/brand/[i:brand_id]",
    function ($route, $parameters) {
        global $brandController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "SUPERADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_SUPERADMIN");
        }

        $result = $brandController->UpdateBrandAction(
            $route,
            $parameters
        );

        return $result;
    },
    "PATCH"
);

// /api/brand - POST

$router->addRoute(
    "/api/brand",
    function ($route, $parameters) {
        global $brandController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "SUPERADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_SUPERADMIN");
        }

        $result = $brandController->CreateBrandAction($route, $parameters);

        return $result;
    },
    "POST"
);

// /api/brands - GET

$router->addRoute(
    "/api/brands",
    function ($route, $parameters) {
        global $brandController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "SUPERADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_SUPERADMIN");
        }

        $result = $brandController->FetchBrandsAction($route, $parameters);

        return $result;
    },
    "GET"
);

// /api/admin - POST

$router->addRoute(
    "/api/admin",
    function ($route, $parameters) {
        global $adminController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "SUPERADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_SUPERADMIN");
        }

        $result = $adminController->CreateAdminAction($route, $parameters);

        return $result;
    },
    "POST"
);

// /api/restaurant - POST

$router->addRoute(
    "/api/restaurant",
    function ($route, $parameters) {
        global $restaurantController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "ADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_ADMIN");
        }

        // It is restaurantController, and not adminController because we are affecting restaurants.
        $result = $restaurantController->CreateRestaurantAction(
            $route,
            $parameters
        );

        return $result;
    },
    "POST"
);

// /api/restaurants - GET

$router->addRoute(
    "/api/restaurants",
    function ($route, $parameters) {
        global $restaurantController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "ADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_ADMIN");
        }

        $result = $restaurantController->FetchRestaurantsAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

// /api/user - POST

$router->addRoute(
    "/api/user",
    function ($route, $parameters) {
        global $userController;

        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "ADMIN")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_ADMIN");
        }

        $result = $userController->CreateUserAction($route, $parameters);

        return $result;
    },
    "POST"
);

// /api/login/admin - POST

$router->addRoute(
    "/api/login/admin",
    function ($route, $parameters) {
        global $adminController;

        $result = $adminController->loginAction($route, $parameters);

        return $result;
    },
    "POST"
);

// /api/login/superadmin - POST

$router->addRoute(
    "/api/login/superadmin",
    function ($route, $parameters) {
        global $superAdminController;

        

        $result = $superAdminController->loginAction($route, $parameters);

        return $result;
    },
    "POST"
);

// /api/dashboard/[s:date] - GET

$router->addRoute(
    "/api/dashboard/[s:date]",
    function ($route, $parameters) {
        
        global $dashboardController;
        
        global $session;

        if(!checkIfLoggedIn($session)) {
            http_response_code(401);
            return new ReturnType(true, "NOT_LOGGED_IN");
        }

        if(!checkRole($session, "USER")) {
            http_response_code(403);
            return new ReturnType(true, "NOT_A_USER");
        }

        $result = $dashboardController->getDashboardDataAction(
            $route,
            $parameters
        );

        return $result;
    },
    "GET"
);

function checkIfLoggedIn(Session $session) {
    $isLoggedIn = $session -> is_logged_in();
    return $isLoggedIn;

}
function checkRole(Session $session, String $role) {
    $actual_role = $session -> role;
    if($role === $actual_role) {
        return true;
    } else {
        return false;
    }
}

/**
 * Non-trivial HTTP requests like POST, PUT, etc... auto-triggers a preflight-request of OPTIONS type.
 * Handling it here.
 */
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    echo "Allow";
} else {
    /**
     * Check if User is Logged In
     */
    // $isLoggedIn = $session->is_logged_in();

    // if ($isLoggedIn == true) {

        // $role = $session->role;

        // $request_uri_and_method =
            // $_SERVER["REQUEST_URI"] . " " . $_SERVER["REQUEST_METHOD"];

        $admin_routes = [
            "/api/restaurant POST",
            "/api/restaurants GET",
            "/api/user POST"
        ];

        $superadmin_routes = [
            "/api/brand POST",
            "/api/brands GET",
            "/api/admin POST",
        ];

        // Routes that need to be accessed by user of all role types.
        $universal_routes = [
            "/api/isloggedin GET",
            "/api/logout GET"
        ];


        $result;
        // Check if the route is a admin_route
        // if (in_array($request_uri_and_method, $admin_routes)) {
            // If the route is admin, check if the user is admin.
            // if ($role == "ADMIN") {
                // $route = explode(" ", $request_uri_and_method)[0];
                // $result = $router->callRoute($route);
            // } else {
                // http_response_code(401);

                // $result = new ReturnType(true, "NOT_AN_ADMIN");
            // }
        // } 
        // Check if the route is superadmin route
        // elseif (in_array($request_uri_and_method, $superadmin_routes)) {
        //     // If the route is superadmin, check if the user is superadmin
        //     if ($role == "SUPERADMIN") {
        //         $route = explode(" ", $request_uri_and_method)[0];
        //         $result = $router->callRoute($route);
        //     } else {
        //         http_response_code(401);

        //         $result = new ReturnType(true, "NOT_A_SUPER_ADMIN");
        //     }
        // } 
        // Neither an admin route nor a superadmin route is being acccessed.
        // else {
            // $result;
            // Accessing a non-existent route may result in an exception which will be caught in this try-catch block.
            try {
                // if(in_array($request_uri_and_method, $universal_routes) || $role == "USER"){
                    
                    $result = $router->callRoute($_SERVER["REQUEST_URI"]);
                // } 
                // else 
                // {
                
                    // http_response_code(401);

                    // $result = new ReturnType(true, "NOT_A_USER");
                
                // }
                // } else {
                    // http_response_code(401);

                    // $result = new ReturnType(true, "NOT_A_USER");
                // }
            } catch (\Throwable $th) {
                http_response_code(404);
                $result = new ReturnType(true, "ROUTE_NOT_FOUND");
            }
        // }

        echo json_encode($result);
        
    // } else {
        $unauthenticated_routes = [
            "/api/login",
            "/api/isloggedin",
            "/api",
            "/api/login/admin",
            "/api/login/superadmin",
        ];

        /**
         * Only accept /login, /isloggedin requests when the user is not logged in, since the user needs these routes to authenticate himself.
         */
        // if (
        //     in_array($_SERVER["REQUEST_URI"], $unauthenticated_routes) ||
        //     preg_match_all("/\/api\/restaurant\/\d*/", $_SERVER["REQUEST_URI"])
        // ) {
        //     $result = $router->callRoute($_SERVER["REQUEST_URI"]);

        //     echo json_encode($result);
        // } /**
        //  * If Un-authenticated User is trying to access secure routes.
        //  */ else {
        //     $result = new ReturnType(true, "NOT_LOGGED_IN");

        //     http_response_code(401);
        //     echo json_encode($result);
        // }
    // }
}

// If OPTIONS, return "Echo Allow"
// If anything else, Check if the user is logged in.
// If the user is not logged in, check if the unauthenticated routes are being accessed.
// If Yes, do them
// If no, return NOT_LOGGED_IN
// If the user if logged_in,
// If admin_route
// Do them
// Return NOT_AN_ADMIN
// If superadmin route
// Do them
// Return NOT_SUPER_ADMIN
// If user route
// Do them
// Return NOT_USER
