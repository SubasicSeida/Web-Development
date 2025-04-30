<?php

// get unavailable rental dates for a property
Flight::route('GET /rentals/unavailable/@property_id', function($property_id) {
    try {
        Flight::json(Flight::rentalService()->getUnavailableDates($property_id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// create a new rental
Flight::route('POST /rental', function() {
    $rentalData = Flight::request()->data->getData();

    try {
        $result = Flight::rentalService()->createRental($rentalData);
        Flight::json(['message' => 'Rental successfully created.', 'rental_id' => $result]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// get all rentals by user ID
Flight::route('GET /rentals/user/@user_id', function($user_id) {
    try {
        Flight::json(Flight::rentalService()->getByUserId($user_id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

