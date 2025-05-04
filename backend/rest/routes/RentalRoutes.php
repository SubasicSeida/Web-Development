<?php

/**
 * @OA\Get(
 *     path="/rentals/unavailable/{property_id}",
 *     summary="Get unavailable rental dates for a specific property",
 *     @OA\Parameter(
 *         name="property_id",
 *         in="path",
 *         required=true,
 *         description="ID of the property",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Unavailable rental date ranges"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid property ID"
 *     )
 * )
 */


// get unavailable rental dates for a property
Flight::route('GET /rentals/unavailable/@property_id', function($property_id) {
    try {
        Flight::json(Flight::rentalService()->getUnavailableDates($property_id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/rental",
 *     summary="Create a new rental booking",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "property_id", "start_date", "end_date"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="property_id", type="integer"),
 *             @OA\Property(property="start_date", type="string", format="date"),
 *             @OA\Property(property="end_date", type="string", format="date")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Rental created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation or conflict error"
 *     )
 * )
 */


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

/**
 * @OA\Get(
 *     path="/rentals/user/{user_id}",
 *     summary="Get all rentals made by a user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of rentals"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid user ID"
 *     )
 * )
 */


// get all rentals by user ID
Flight::route('GET /rentals/user/@user_id', function($user_id) {
    try {
        Flight::json(Flight::rentalService()->getByUserId($user_id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

