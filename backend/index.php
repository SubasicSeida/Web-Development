<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/PropertyService.php';
require_once __DIR__ . '/rest/services/PropertyImageService.php';
require_once __DIR__ . '/rest/services/FavoriteService.php';
require_once __DIR__ . '/rest/services/RentalService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/roles.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('userService', 'UserService');
Flight::register('propertyService', 'PropertyService');
Flight::register('propertyImageService', 'PropertyImageService');
Flight::register('favoriteService', 'FavoriteService');
Flight::register('rentalService', 'RentalService');
Flight::register('reviewService', 'ReviewService');
Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', 'AuthMiddleware');

Flight::route('/*', function() {
    $url = Flight::request()->url;

    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 ||
        (strpos($url, '/property/') === 0 && preg_match('#^/property/\d+/images#', $url)) ||
        strpos($url, '/properties/search') === 0 ||
        strpos($url, '/rentals/unavailable/') === 0 ||
        (strpos($url, '/reviews/property/') === 0 && (
            preg_match('#^/reviews/property/\d+$#', $url) ||
            preg_match('#^/reviews/property/\d+/average$#', $url)
        ))
    ) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});


require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/PropertyRoutes.php';
require_once __DIR__ . '/rest/routes/PropertyImageRoutes.php'; 
require_once __DIR__ . '/rest/routes/FavoriteRoutes.php';
require_once __DIR__ . '/rest/routes/RentalRoutes.php'; 
require_once __DIR__ . '/rest/routes/ReviewRoutes.php'; 
require_once __DIR__ . '/rest/routes/AuthRoutes.php'; 

Flight::start();

