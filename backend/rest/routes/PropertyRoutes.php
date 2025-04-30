<?php

// get properties by agent ID
Flight::route('GET /properties/agent/@id', function($id) {
    $page = Flight::request()->query['page'] ?? 1;
    Flight::json(Flight::propertyService()->getByAgentId((int)$id, (int)$page));
});

// search properties with filters
Flight::route('GET /properties/search', function() {
    $filters = Flight::request()->query->getData();
    try {
        Flight::json(Flight::propertyService()->searchProperties($filters));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// get property by ID
Flight::route('GET /property/@id', function($id) {
    Flight::json(Flight::propertyService()->getById($id));
});

// create a new property
Flight::route('POST /property', function() {
    $data = Flight::request()->data->getData();
    try {
        Flight::json(Flight::propertyService()->create($data));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// update property by ID
Flight::route('PUT /property/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        Flight::json(Flight::propertyService()->update($id, $data));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// delete property by ID
Flight::route('DELETE /property/@id', function($id) {
    try {
        Flight::json(Flight::propertyService()->delete($id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

