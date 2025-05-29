<?php

/**
 * @OA\Get(
 *     path="/reviews/property/{id}",
 *     summary="Get paginated reviews for a property",
 *     tags={"Reviews"},
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
    // doesn't need auth bcs it's public info

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
 *     tags={"Reviews"},
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
    // doesn't need auth bcs it's public info

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
 *     tags={"Reviews"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $data = Flight::request()->data->getData();
    try {
        $id = Flight::reviewService()->createReview($data);
        Flight::json(['message' => 'Review submitted successfully.', 'review_id' => $id]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     summary="User deletes their own review",
 *     tags={"Reviews"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the review to delete",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or unauthorized"
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER);

    $user = Flight::get('user');

    try {
        Flight::reviewService()->deleteReviewByUserId($user['id'], $id);
        Flight::json(['message' => 'Review deleted.']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/admin/reviews/{id}",
 *     summary="Admin deletes any review",
 *     tags={"Reviews"},
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID to delete",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error deleting review"
 *     )
 * )
 */
Flight::route('DELETE /admin/reviews/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    try {
        Flight::reviewService()->deleteAnyReview($id);
        Flight::json(['message' => 'Review deleted by admin.']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
