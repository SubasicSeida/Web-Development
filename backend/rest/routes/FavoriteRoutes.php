<?php

/**
 * @OA\Get(
 *     path="/favorites/user/{id}",
 *     summary="Get paginated list of a user's favorite properties",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", default=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of favorite properties"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid user ID or page"
 *     )
 * )
 */

// get paginated favorites
Flight::route('GET /favorites/user/@id', function($id) {
    $page = Flight::request()->query['page'] ?? 1;

    try {
        Flight::json(Flight::favoriteService()->getFavoritesByUserId($id, $page));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/favorites/check",
 *     summary="Check if a user has favorited a specific property",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="property_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Favorited status"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or missing parameters"
 *     )
 * )
 */

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

