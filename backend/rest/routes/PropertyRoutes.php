<?php

/**
 * @OA\Get(
 *     path="/properties/agent/{id}",
 *     summary="Get paginated properties listed by a specific agent",
 *     tags={"Properties"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Agent ID",
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
 *         description="List of properties by agent"
 *     )
 * )
 */


// get properties by agent ID
Flight::route('GET /properties/agent/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::AGENT, Roles::ADMIN);

    $page = Flight::request()->query['page'] ?? 1;
    Flight::json(Flight::propertyService()->getByAgentId($id, $page));
});

/**
 * @OA\Get(
 *     path="/properties/search",
 *     summary="Search properties using filters",
 *     tags={"Properties"},
 *     @OA\Parameter(name="keyword", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="propertyType", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="listingType", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="minPrice", in="query", @OA\Schema(type="number")),
 *     @OA\Parameter(name="maxPrice", in="query", @OA\Schema(type="number")),
 *     @OA\Parameter(name="minSize", in="query", @OA\Schema(type="number")),
 *     @OA\Parameter(name="maxSize", in="query", @OA\Schema(type="number")),
 *     @OA\Parameter(name="bedrooms", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="bathrooms", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="order", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Filtered property results"),
 *     @OA\Response(response=400, description="Validation error")
 * )
 */


// search properties with filters
Flight::route('GET /properties/search', function() {
    // doesn't need auth bcs it's public info
    
    $filters = Flight::request()->query->getData();
    try {
        Flight::json(Flight::propertyService()->searchProperties($filters));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/property/{id}",
 *     summary="Update property details",
 *     tags={"Properties"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Property ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="price", type="number"),
 *             @OA\Property(property="sqft", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Property updated"),
 *     @OA\Response(response=400, description="Update error")
 * )
 */


// update property by ID
Flight::route('PUT /property/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::AGENT]);

    $data = Flight::request()->data->getData();
    try {
        Flight::json(Flight::propertyService()->update($id, $data));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/property",
 *     summary="Create a new property",
 *     tags={"Properties"},
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description", "price", "property_type", "sqft", "address", "city", "listing_type", "agent_id"},
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="price", type="number"),
 *             @OA\Property(property="property_type", type="string"),
 *             @OA\Property(property="sqft", type="integer"),
 *             @OA\Property(property="address", type="string"),
 *             @OA\Property(property="city", type="string"),
 *             @OA\Property(property="zip_code", type="string"),
 *             @OA\Property(property="listing_type", type="string"),
 *             @OA\Property(property="bedrooms", type="integer"),
 *             @OA\Property(property="bathrooms", type="integer"),
 *             @OA\Property(property="agent_id", type="integer"),
 *             @OA\Property(property="country", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Property created successfully"),
 *     @OA\Response(response=400, description="Validation error")
 * )
 */


// create a new property
Flight::route('POST /property', function() {
    Flight::auth_middleware()->authorizeRole(Roles::AGENT);

    $data = Flight::request()->data->getData();
    try {
        Flight::json(Flight::propertyService()->create($data));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/property/{id}",
 *     summary="Delete a property by ID",
 *     tags={"Properties"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Property ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Property deleted"),
 *     @OA\Response(response=400, description="Invalid property ID")
 * )
 */


// delete property by ID
Flight::route('DELETE /property/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::AGENT, Roles::ADMIN]);

    try {
        Flight::json(Flight::propertyService()->delete($id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

