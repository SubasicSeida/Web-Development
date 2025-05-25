<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
Flight::group('/auth', function() {
   /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new customer",
     *     description="Registers a new user with the required fields",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "first_name", "phone_number"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="demo@gmail.com",
     *                 description="User's email address"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 minLength=8,
     *                 example="strongpassword123",
     *                 description="User's password (minimum 8 characters)"
     *             ),
     *             @OA\Property(
     *                 property="first_name",
     *                 type="string",
     *                 example="John",
     *                 description="User's first name"
     *             ),
     *             @OA\Property(
     *                 property="phone_number",
     *                 type="string",
     *                 example="+1234567890",
     *                 description="User's phone number"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or missing fields",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Email already registered.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

   Flight::route("POST /register", function () {
       $data = Flight::request()->data->getData();


       $response = Flight::auth_service()->register($data);
  
       if ($response['success']) {
           Flight::json([
               'message' => 'User registered successfully',
               'data' => $response['data']
           ]);
       } else {
           Flight::halt(500, $response['error']);
       }
   });
   /**
    * @OA\Post(
    *      path="/auth/login",
    *      tags={"auth"},
    *      summary="Login to system using email and password",
    *      @OA\Response(
    *           response=200,
    *           description="User data and JWT"
    *      ),
    *      @OA\RequestBody(
    *          description="Credentials",
    *          @OA\JsonContent(
    *              required={"email","password"},
    *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="Student email address"),
    *              @OA\Property(property="password", type="string", example="some_password", description="Student password")
    *          )
    *      )
    * )
    */
   Flight::route('POST /login', function() {
    try {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::json([
                'success' => false,
                'error' => $response['error']
            ], 401);
        }

    } catch (Exception $e) {
        Flight::json([
            'success' => false,
            'error' => 'Internal server error'
        ], 500);
    }
});

});
?>
