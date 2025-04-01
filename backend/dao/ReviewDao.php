<?php
require_once "BaseDao.php";

class ReviewDao extends BaseDao {
    public function __construct($table = "reviews") {
        parent::__construct($table);
    }

    public function getByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE user_id = :userId");
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPropertyId($propertyId) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE property_id = :propertyId");
        $stmt->bindParam(":propertyId", $propertyId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByRating($rating) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE rating = :rating");
        $stmt->bindParam(":rating", $rating);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>