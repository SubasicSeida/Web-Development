<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao {
    public function __construct($table = "users") {
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
        return $stmt->fetch();
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
}

?>