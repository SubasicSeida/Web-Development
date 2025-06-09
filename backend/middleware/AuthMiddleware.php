<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function verifyToken($token) {
        if(!$token) {
            Flight::halt(401, "Missing authentication header");
            return FALSE;
        }

        try {
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

            $now = new DateTimeImmutable();
            if (isset($decoded_token->exp) && $decoded_token->exp < $now->getTimestamp()) {
                throw new Exception("Token has expired");
            }

            Flight::set('user', $decoded_token->user);
            Flight::set('jwt_token', $token);
            
            return TRUE;
        } catch (Exception $e) {
            Flight::halt(401, "Invalid token: " . $e->getMessage());
            return false;
        }
    }

    public function authorizeRole($requiredRole) {
        $user = Flight::get('user');

        if($user->user_role !== $requiredRole) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles($roles) {
        $user = Flight::get('user');

        if(!in_array($user->user_role, $roles)) {
            Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    function authorizePermission($permission) {
        $user = Flight::get('user');

        if(!in_array($permission, $user->permissions)) {
            Flight::halt(403, 'Access denied: permission missing');
        }
    }
}