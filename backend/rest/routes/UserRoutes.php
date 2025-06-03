<?php

/**
 * @OA\Get(
 *     path="/user/id",
 *     summary="Get user by id",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="User data"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid id"
 *     )
 * )
 */

Flight::route('GET /user/id', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::AGENT]);

    $user = Flight::get('user');

    try {
        Flight::json(Flight::userService()->getById($user->id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/user/email/{email}",
 *     summary="Get user by email",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

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
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    
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
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRoles([Roles::AGENT, Roles::CUSTOMER]);

    $data = Flight::request()->data->getData();
    $newPassword = $data['password'] ?? '';

    try {
        Flight::json(Flight::userService()->updatePassword($id, $newPassword));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/user/{id}/profile-picture",
 *     summary="Update user's profile picture",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to update",
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"profile_picture"},
 *             @OA\Property(
 *                 property="profile_picture",
 *                 type="string",
 *                 description="Path or URL to the new profile picture",
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile picture updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or user ID"
 *     )
 * )
 */
Flight::route('PUT /user/@id/profile-picture', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::AGENT, Roles::CUSTOMER]);

    $data = Flight::request()->data->getData();
    $picturePath = $data['profile_picture'] ?? null;

    try {
        $result = Flight::userService()->updateProfilePicture($id, $picturePath);
        Flight::json(['message' => 'Profile picture updated.']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/agent",
 *     summary="Create a new agent",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();

    $result = Flight::userService()->createAgent($data);
    if (is_array($result)) {
        Flight::json(['errors' => $result], 400);
    } else {
        Flight::json(['message' => 'Agent created successfully.', 'agent_id' => $result]);
    }
});

/**
 * @OA\Delete(
 *     path="/user/{id}",
 *     summary="Delete a customer user account",
 *     description="Allows deletion of a user account only if the user has the role 'customer'.",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to delete",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error or unauthorized attempt"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('DELETE /user/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);

    try {
        $result = Flight::userService()->deleteUser($id);
        if ($result) {
            Flight::json(['message' => 'User deleted successfully.']);
        } else {
            Flight::json(['error' => 'Deletion failed.'], 500);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/user/agent/{id}",
 *     summary="Admin: Delete an agent account",
 *     tags={"Users"},
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Agent user ID",
 *         @OA\Schema(type="integer", example=12)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Agent deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error deleting agent"
 *     )
 * )
 */
Flight::route('DELETE /user/agent/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    try {
        Flight::userService()->deleteAgentByAdmin($id);
        Flight::json(['message' => 'Agent account deleted.']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

