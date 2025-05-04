<?php

/**
 * @OA\Get(
 *     path="/property/{id}/images",
 *     summary="Get all images for a specific property",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the property",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of property images"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid property ID or server error"
 *     )
 * )
 */

// get all property images
Flight::route('GET /property/@id/images', function($id) {
    try {
        Flight::json(Flight::propertyImagesService()->getByPropertyId($id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

