<?php

require_once 'dao/BaseDao.php';
require_once 'dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function getByRole($role) {
        return $this->dao->getByRole($userRole);
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

    public function createUser($userData) {
        $this->validateUserData($userData);
        
        if ($this->dao->getByEmail($userData['email'])) {
            throw new RuntimeException("Email already exists");
        }

        $hashedPassword = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        return $this->dao->insert([
            'email' => $userData['email'],
            'password_hash' => $hashedPassword,
            'user_role' => $userData['role'] ?? 'customer',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function validateUserData($userData) {
        $required = ['email', 'password'];
        foreach ($required as $field) {
            if (empty($userData[$field])) {
                throw new InvalidArgumentException("Missing required field: $field");
            }
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format");
        }

        if (strlen($userData['password']) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters");
        }
    }
}

?>