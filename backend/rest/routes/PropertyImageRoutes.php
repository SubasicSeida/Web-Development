<?php

/**
 * @OA\Get(
 *     path="/property/{id}/images",
 *     summary="Get all images for a specific property",
 *     tags={"Properties"},
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
    // doesn't need auth bcs it's public info
    try {
        Flight::json(Flight::propertyImageService()->getByPropertyId($id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/property/{id}/uploadImages",
 *     summary="Upload new images for a property",
 *     tags={"Properties"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         description="Array of image URLs to add",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="images",
 *                     type="array",
 *                     @OA\Items(
 *                         type="string",
 *                         description="URL of the image"
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Images successfully uploaded"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or property ID"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - authentication required"
 *     )
 * )
 */

// create new property images
Flight::route('POST /property/@id/uploadImages', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::AGENT);

    $data = Flight::request()->data->getData();
    
    if (!isset($data['images']) || !is_array($data['images'])) {
        Flight::json(['error' => 'Invalid image data format'], 400);
        return;
    }

    try {
        $createdImages = Flight::propertyImageService()->createImagesForProperty($id, $data['images']);
        Flight::json($createdImages, 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});