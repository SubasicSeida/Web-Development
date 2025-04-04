<?php
require_once 'BaseDao.php';

class FavoritesDao extends BaseDao {
    public function __construct($table = "favorites") {
        parent::__construct($table);
    }

    public function getByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM favorites WHERE user_id = :userId");
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>