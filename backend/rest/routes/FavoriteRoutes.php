<?php

/**
 * @OA\Get(
 *     path="/favorites/user/{id}",
 *     summary="Get paginated list of a user's favorite properties",
 *     tags={"Favorites"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $page = Flight::request()->query['page'] ?? 1;

    try {
        Flight::json(Flight::favoriteService()->getPaginatedFavorites($id, $page));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/favorites",
 *     summary="Get all user's favorite properties",
 *     tags={"Favorites"},
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of property ids"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid user ID"
 *     )
 * )
 */
Flight::route('GET /favorites', function() {
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);
    $user = Flight::get('user'); 

    try {
        Flight::json(Flight::favoriteService()->getFavoritesByUserId($user->id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/favorites/check",
 *     summary="Check if a user has favorited a specific property",
 *     tags={"Favorites"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $userId = Flight::request()->query['user_id'] ?? null;
    $propertyId = Flight::request()->query['property_id'] ?? null;

    try {
        $isFavorited = Flight::favoriteService()->isFavorited($userId, $propertyId);
        Flight::json(['favorited' => $isFavorited]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/favorites/{id}/add",
 *     summary="Add a property to favorites",
 *     tags={"Favorites"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Property ID",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Property added to favorites successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or failed"
 *     )
 * )
*/
Flight::route('POST /favorites/@id/add', function($propertyId) {
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $user = Flight::get('user');

    try {
        Flight::favoriteService()->createFavorite($user->id, $propertyId);
        Flight::json(['message' => 'Property favorited.']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/favorites/{id}/remove",
 *     summary="Remove property from favorites",
 *     tags={"Favorites"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Property ID",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Property removed from favorites successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or failed"
 *     )
 * )
 */
Flight::route('DELETE /favorites/@id/remove', function($propertyId) {
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $user = Flight::get('user');

    try {
        Flight::favoriteService()->removeFavorite($user->id, $propertyId);
        Flight::json(['message' => 'Property unfavorited.']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});