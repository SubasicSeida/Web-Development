<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {
    protected $table;

    public function __construct() {
        $this->table = "users";
        parent::__construct($table);
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM  users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByRole($userRole) {
        $stmt = $this->connection->prepare("SELECT * FROM  users WHERE user_role = :userRole");
        $stmt->bindParam(':userRole', $userRole);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updatePassword($userId, $newPasswordHash) {
        $stmt = $this->connection->prepare("UPDATE users 
                                            SET password_hash = :newPasswordHash,
                                            password_changed_at = CURRENT_TIMESTAMP() 
                                            WHERE id = :userId");
        $stmt->bindParam(':newPasswordHash', $newPasswordHash);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function createCustomer($userData) {
        $userRole = 'customer';
        $stmt = $this->connection->prepare("INSERT INTO users 
                                          (first_name, last_name, email, password_hash, phone_number, user_role,
                                          profile_picture, created_at) 
                                          VALUES (:first_name, :last_name, :email, :password_hash, :phone_number, :user_role,
                                          :profile_picture, NOW())");
        
        $stmt->bindParam(':first_name', $userData['first_name']);
        $stmt->bindParam(':last_name', $userData['last_name']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password_hash', $userData['password_hash']);
        $stmt->bindParam(':phone_number', $userData['phone_number']);
        $stmt->bindParam(':user_role', $userRole);
        $stmt->bindParam(':profile_picture', $userData['profile_picture']);
        
        if ($stmt->execute()) {
            return $this->connection->lastInsertId();
        } else {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    public function createAgent($userData) {
        $userRole = 'agent';
        $stmt = $this->connection->prepare("INSERT INTO users 
                                          (first_name, last_name, email, password_hash, phone_number, user_role,
                                          profile_picture, created_at) 
                                          VALUES (:first_name, :last_name, :email, :password_hash, :phone_number, :user_role,
                                          :profile_picture, NOW())");
        
        $stmt->bindParam(':first_name', $userData['first_name']);
        $stmt->bindParam(':last_name', $userData['last_name']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password_hash', $userData['password_hash']);
        $stmt->bindParam(':phone_number', $userData['phone_number']);
        $stmt->bindParam(':user_role', $userRole);
        $stmt->bindParam(':profile_picture', $userData['profile_picture']);
        
        if ($stmt->execute()) {
            return $this->connection->lastInsertId();
        } else {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
    }
}

?>