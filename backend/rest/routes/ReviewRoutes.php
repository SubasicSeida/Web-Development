<?php

/**
 * @OA\Get(
 *     path="/reviews/property/{id}",
 *     summary="Get paginated reviews for a property",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Property ID",
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
 *         description="List of reviews for the property"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid property ID or page"
 *     )
 * )
 */


// get paginated reviews for a property
Flight::route('GET /reviews/property/@id', function($id) {
    $page = Flight::request()->query['page'] ?? 1;

    try {
        Flight::json(Flight::reviewService()->getByPropertyId($id, $page));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/reviews/property/{id}/average",
 *     summary="Get average rating for a property",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Property ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Average rating value"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid property ID"
 *     )
 * )
 */


// get average rating for a property
Flight::route('GET /reviews/property/@id/average', function($id) {
    try {
        $avg = Flight::reviewService()->getAverageRating($id);
        Flight::json(['average_rating' => round($avg, 2)]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/review",
 *     summary="Submit a new review for a property",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "property_id", "rating"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="property_id", type="integer"),
 *             @OA\Property(property="rating", type="number", format="float", minimum=1, maximum=5),
 *             @OA\Property(property="comment", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Review successfully submitted"),
 *     @OA\Response(response=400, description="Invalid input")
 * )
 */
Flight::route('POST /review', function() {
    $data = Flight::request()->data->getData();
    try {
        $id = Flight::reviewService()->createReview($data);
        Flight::json(['message' => 'Review submitted successfully.', 'review_id' => $id]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
