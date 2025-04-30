<?php

// get a user by email
Flight::route('GET /user/email/@email', function($email) {
    try {
        Flight::json(Flight::userService()->getByEmail($email));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// get users by role
Flight::route('GET /users/role/@role', function($role) {
    try {
        Flight::json(Flight::userService()->getByRole($role));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// update password 
Flight::route('PUT /user/password/@id', function($id) {
    $data = Flight::request()->data->getData();
    $newPassword = $data['password'] ?? '';

    try {
        Flight::json(Flight::userService()->updatePassword($id, $newPassword));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

// create a new customer
Flight::route('POST /customer', function() {
    $data = Flight::request()->data->getData();

    $result = Flight::userService()->createCustomer($data);
    if (is_array($result)) {
        Flight::json(['errors' => $result], 400);
    } else {
        Flight::json(['message' => 'Customer created successfully.', 'customer_id' => $result]);
    }
});

// create a new agent
Flight::route('POST /agent', function() {
    $data = Flight::request()->data->getData();

    $result = Flight::userService()->createAgent($data);
    if (is_array($result)) {
        Flight::json(['errors' => $result], 400);
    } else {
        Flight::json(['message' => 'Agent created successfully.', 'agent_id' => $result]);
    }
});

