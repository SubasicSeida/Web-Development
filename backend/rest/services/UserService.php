<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function getByEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        return $this->dao->getByEmail($email);
    }

    public function getByRole($role) {
        $allowedRoles = ['admin', 'agent', 'customer'];
        if (!in_array($role, $allowedRoles, true)) {
            throw new Exception("Invalid user role.");
        }
        return $this->dao->getByRole($role);
    }

    public function updatePassword($userId, $newPassword) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new Exception("Invalid user ID.");
        }

        if (strlen($newPassword) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->dao->updatePassword($userId, $hashed);
    }

    public function createCustomer($userData) {
        $errors = $this->validateUserData($userData);
        
        if (!empty($errors)) {
            return $errors;
        }

        $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        unset($userData['password']);

        return $this->dao->createCustomer($userData);
    }

    public function createAgent($userData) {
        $errors = $this->validateUserData($userData);
        
        if (!empty($errors)) {
            return $errors;
        }

        $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        unset($userData['password']);

        return $this->dao->createAgent($userData);
    }

    private function validateUserData($userData) {
        $errors = [];

        if (empty($userData['first_name'])) {
            $errors[] = 'First name is required.';
        }

        if (empty($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if (empty($userData['password']) || strlen($userData['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        if (!empty($userData['phone_number']) && !preg_match('/^\+?[0-9]{7,15}$/', $userData['phone_number'])) {
            $errors[] = 'Phone number must be between 7 and 15 digits, optionally starting with +.';
        }

        return $errors;
    }
}

?>