<?php

// get paginated reviews for a property
Flight::route('GET /reviews/property/@id', function($id) {
    $page = Flight::request()->query['page'] ?? 1;

    try {
        Flight::json(Flight::reviewService()->getByPropertyId($id, $page));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// get average rating for a property
Flight::route('GET /reviews/property/@id/average', function($id) {
    try {
        $avg = Flight::reviewService()->getAverageRating($id);
        Flight::json(['average_rating' => round($avg, 2)]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

