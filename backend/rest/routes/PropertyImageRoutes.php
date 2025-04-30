<?php

// get all property images
Flight::route('GET /property/@id/images', function($id) {
    try {
        Flight::json(Flight::propertyImagesService()->getByPropertyId($id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

