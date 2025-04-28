<?php

require_once 'BaseService.php';
require_once '../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function getByRole($role) {
        return $this->dao->getByRole($role);
    }

    public function updatePassword($userId, $newPassHash) {
        if (empty($userId) || !is_numeric($userId)) {
            throw new InvalidArgumentException("Invalid user ID");
        }

        if (empty($newPasswordHash) || strlen($newPasswordHash) < 60) {
            throw new InvalidArgumentException("Invalid password hash");
        }

        return $this->dao->updatePassword($userId, $newPasswordHash);
    }

    public function createCustomer($userData) {
        $errors = $this->validateUserData($userData);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $customerId = $this->userDao->createCustomer($userData);
        return ['success' => true, 'customerId' => $customerId];
    }

    public function createAgent($userData) {
        $errors = $this->validateUserData($userData);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $agentId = $this->userDao->createCustomer($userData);
        return ['success' => true, 'agentId' => $agentId];
    }

    private function validateUserData($userData) {
        $errors = [];

        if (empty($userData['first_name'])) {
            $errors[] = 'First name is required.';
        }

        if (empty($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if (empty($userData['password_hash'])) {
            $errors[] = 'Password hash is required.';
        }

        if (!empty($userData['phone_number']) && !preg_match('/^\+?[0-9]{7,15}$/', $userData['phone_number'])) {
            $errors[] = 'Phone number must be between 7 and 15 digits, optionally starting with +.';
        }

        return $errors;
    }
}

?>