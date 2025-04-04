<?php
require_once 'UserDao.php';
require_once 'PropertyDao.php';
require_once 'FavoritesDao.php';
require_once 'ReviewDao.php';

/*

$userDao = new UserDao();

// insert a new user

$userDao->insert([
    'first_name' => 'Bob',
    'last_name' => 'Smith',
    'email' => 'bob@example.com',
    'password_hash' => password_hash('password345', PASSWORD_DEFAULT),
    'phone_number' => '75285432',
    'user_role' => 'agent',
    'profile_picture' => 'ffgjdevl',
    'created_at' => date('Y-m-d H:i:s'),
    'password_changed_at' => date('Y-m-d H:i:s')
]);

// fetch all users
$users = $userDao->getAll();
print_r($users);



$propertyDao = new PropertyDao();

// Test data for insertion
$propertyDao->insert([
    'title' => 'Modern Apartment in Sarajevo',
    'description' => 'A beautiful 2-bedroom apartment in the center of Sarajevo.',
    'price' => 250000,
    'property_type' => 'apartment',
    'sqft' => 80,
    'bedrooms' => 2,
    'bathrooms' => 1,
    'city' => 'Sarajevo',
    'listing_type' => 'sale',
    'agent_id' => 3,
    'created_at' => date('Y-m-d H:i:s')
]);


// Fetch all properties
echo "All Properties:\n";
print_r($propertyDao->getAll());

// Fetch by title
echo "\nBy Title:\n";
print_r($propertyDao->getByTitle('Modern Apartment in Sarajevo'));

// Fetch by price range
echo "\nBy Price Range:\n";
print_r($propertyDao->getByPriceRange(200000, 300000));

// Fetch by property type
echo "\nBy Property Type:\n";
print_r($propertyDao->getByPropertyType('apartment'));

// Fetch by square footage
echo "\nBy Square Footage:\n";
print_r($propertyDao->getBySqft(60, 100));

// Fetch by city
echo "\nBy City:\n";
print_r($propertyDao->getByCity('Ljubljana'));

// Fetch by listing type
echo "\nBy Listing Type:\n";
print_r($propertyDao->getByListingType('sale'));

// Fetch by bedroom count
echo "\nBy Bedroom Count:\n";
print_r($propertyDao->getByBedroomCount(2));

// Fetch by bathroom count
echo "\nBy Bathroom Count:\n";
print_r($propertyDao->getByBathroomCount(1));

// Fetch by agent ID
echo "\nBy Agent ID:\n";
print_r($propertyDao->getByAgentId(3));

// Fetch by keyword
echo "\nProperties with 'Apartment' in title:\n";
print_r($propertyDao->getByKeyword("apartment"));



$favoritesDao = new FavoritesDao();

// test insertion
$favoritesDao->insert([
    'user_id' => 1,
    'property_id' => 2,
    'created_at' => date('Y-m-d H:i:s')
]);

// Fetch by user id
print_r($favoritesDao->getByUserId(1));

*/

$reviewDao = new ReviewDao();

$reviewDao->insert([
    'user_id' => 1,
    'property_id' => 2,
    'rating' => 4,
    'comment' => 'Great property! Loved the space and location.',
    'created_at' => date('Y-m-d H:i:s')
]);

// fetch by user id 
print_r($reviewDao->getByUserId(1));

// fetch by property id
print_r($reviewDao->getByPropertyId(2));

// fetch by rating
print_r($reviewDao->getByRating(4));

?>