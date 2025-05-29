<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {
    protected $table;

    public function __construct() {
        $this->table = "users";
        parent::__construct($this->table);
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
    
}

?>