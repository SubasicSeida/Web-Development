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

        $user = parent::getById($userId);
        if (!$user) {
            throw new Exception("User not found.");
        }

        if (password_verify($newPassword, $user['password_hash'])) {
            throw new Exception("New password must be different from the current password.");
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        return parent::update($userId, ['password_hash' => $hashed]);
    }

    public function createAgent($userData) {
        $errors = $this->validateUserData($userData);
        
        if (!empty($errors)) {
            return $errors;
        }

        $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        unset($userData['password']);
        $userData['role'] = 'agent';

        return parent::create($userData);
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

    public function updateProfilePicture($userId, $picturePath) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new InvalidArgumentException("Invalid user ID.");
        }

        if (empty($picturePath) || !is_string($picturePath)) {
            throw new InvalidArgumentException("Invalid profile picture path.");
        }

        return parent::update($userId, [
            'profile_picture' => $picturePath
        ]);
    }


    public function deleteUser($userId) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new InvalidArgumentException("Invalid user ID.");
        }

        $user = parent::getById(($userId));
        if(!$user){
            throw new Exception("User not found.");
        }

        if ($user['user_role'] !== 'customer') {
            throw new Exception("Only customers can delete their accounts.");
        }

        return parent::delete($userId);
    }

    public function deleteAgentByAdmin($userId) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new Exception("Invalid user ID.");
        }

        $user = $this->dao->getById($userId);
        if (!$user) {
            throw new Exception("User not found.");
        }

        if ($user['role'] !== 'agent') {
            throw new Exception("Selected user is not an agent.");
        }

        $deleted = parent::delete($userId);
        if (!$deleted) {
            throw new Exception("Failed to delete agent.");
        }

        return true;
    }

}

?>