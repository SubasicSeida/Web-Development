<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/PropertyService.php';
require_once __DIR__ . '/rest/services/PropertyImageService.php';
require_once __DIR__ . '/rest/services/FavoriteService.php';
require_once __DIR__ . '/rest/services/RentalService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require_once __DIR__ . '/rest/services/AuthService.php';

Flight::register('userService', 'UserService');
Flight::register('propertyService', 'PropertyService');
Flight::register('propertyImageService', 'PropertyImageService');
Flight::register('favoriteService', 'FavoriteService');
Flight::register('rentalService', 'RentalService');
Flight::register('reviewService', 'ReviewService');
Flight::register('auth_service', 'AuthService');

require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/PropertyRoutes.php';
require_once __DIR__ . '/rest/routes/PropertyImageRoutes.php'; 
require_once __DIR__ . '/rest/routes/FavoriteRoutes.php';
require_once __DIR__ . '/rest/routes/RentalRoutes.php'; 
require_once __DIR__ . '/rest/routes/ReviewRoutes.php'; 
require_once __DIR__ . '/rest/routes/AuthRoutes.php'; 

Flight::start();

