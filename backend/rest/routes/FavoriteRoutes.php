<?php

// get paginated favorites
Flight::route('GET /favorites/user/@id', function($id) {
    $page = Flight::request()->query['page'] ?? 1;

    try {
        Flight::json(Flight::favoriteService()->getFavoritesByUserId($id, $page));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// check if a property is favorited by a user
Flight::route('GET /favorites/check', function() {
    $userId = Flight::request()->query['user_id'] ?? null;
    $propertyId = Flight::request()->query['property_id'] ?? null;

    try {
        $isFavorited = Flight::favoriteService()->isFavorited($userId, $propertyId);
        Flight::json(['favorited' => $isFavorited]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

