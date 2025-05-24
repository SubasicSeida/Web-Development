<?php

/**
 * @OA\Get(
 *     path="/rentals/unavailable/{property_id}",
 *     summary="Get unavailable rental dates for a specific property",
 *     tags={"Rentals"},
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
    // doesn't need auth bcs it's public info

    try {
        Flight::json(Flight::rentalService()->getUnavailableDates($property_id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/rentals/user/{user_id}",
 *     summary="Get all rentals made by a user",
 *     tags={"Rentals"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    try {
        Flight::json(Flight::rentalService()->getByUserId($user_id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/rentals/property/{propertyId}",
 *     summary="Get all rentals with details for a specific property",
 *     tags={"Rentals"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="propertyId",
 *         in="path",
 *         required=true,
 *         description="Property ID",
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of rentals for the property with details",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=101),
 *                 @OA\Property(property="user_id", type="integer", example=4),
 *                 @OA\Property(property="property_id", type="integer", example=7),
 *                 @OA\Property(property="start_date", type="string", format="date", example="2025-06-01"),
 *                 @OA\Property(property="end_date", type="string", format="date", example="2025-06-05"),
 *                 @OA\Property(property="status", type="string", example="confirmed")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid property ID"
 *     )
 * )
 */
Flight::route('GET /rentals/property/@propertyId', function($propertyId) {
    Flight::auth_middleware()->authorizeRole([Roles::AGENT, Roles::ADMIN]);

    try {
        $rentals = Flight::rentalService()->getRentalDetailsByPropertyId($propertyId);
        Flight::json($rentals);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/rental",
 *     summary="Create a new rental booking",
 *     tags={"Rentals"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $rentalData = Flight::request()->data->getData();

    try {
        $result = Flight::rentalService()->createRental($rentalData);
        Flight::json(['message' => 'Rental successfully created.', 'rental_id' => $result]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/rentals/{id}",
 *     summary="Cancel a rental",
 *     tags={"Rentals"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Rental ID to cancel",
 *         @OA\Schema(type="integer", example=12)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Rental cancelled successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid rental ID or cancel failed"
 *     )
 * )
 */
Flight::route('DELETE /rentals/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);

    try {
        $result = Flight::rentalService()->cancelRental($id);
        if ($result) {
            Flight::json(['message' => 'Rental cancelled.']);
        } else {
            Flight::json(['error' => 'Rental not found or cancel failed.'], 400);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
