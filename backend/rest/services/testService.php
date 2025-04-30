<?php

require_once 'PropertyService.php';
require_once 'FavoriteService.php';
require_once 'PropertyImageService.php';
require_once 'RentalService.php';
require_once 'ReviewService.php';
require_once 'UserService.php';

$service = new UserService();

echo "=== Testing getByEmail ===\n";
try {
    $user = $service->getByEmail("john@example.com");
    print_r($user);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>