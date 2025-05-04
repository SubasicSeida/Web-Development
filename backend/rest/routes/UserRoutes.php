<?php

/**
 * @OA\Get(
 *     path="/user/email/{email}",
 *     summary="Get user by email",
 *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", format="email")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User data"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid email format"
 *     )
 * )
 */

// get a user by email
Flight::route('GET /user/email/@email', function($email) {
    try {
        Flight::json(Flight::userService()->getByEmail($email));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/users/role/{role}",
 *     summary="Get users by role",
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", enum={"admin", "agent", "customer"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Users with the specified role"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid user role"
 *     )
 * )
 */

// get users by role
Flight::route('GET /users/role/@role', function($role) {
    try {
        Flight::json(Flight::userService()->getByRole($role));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/user/password/{id}",
 *     summary="Update user password",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"password"},
 *             @OA\Property(property="password", type="string", minLength=8)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password updated"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */

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

/**
 * @OA\Post(
 *     path="/customer",
 *     summary="Create a new customer",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "email", "password"},
 *             @OA\Property(property="first_name", type="string"),
 *             @OA\Property(property="last_name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", minLength=8),
 *             @OA\Property(property="phone_number", type="string"),
 *             @OA\Property(property="profile_picture", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Customer created"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation errors"
 *     )
 * )
 */

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

/**
 * @OA\Post(
 *     path="/agent",
 *     summary="Create a new agent",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "email", "password"},
 *             @OA\Property(property="first_name", type="string"),
 *             @OA\Property(property="last_name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", minLength=8),
 *             @OA\Property(property="phone_number", type="string"),
 *             @OA\Property(property="profile_picture", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent created"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation errors"
 *     )
 * )
 */

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

