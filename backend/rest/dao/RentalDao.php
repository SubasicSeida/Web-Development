<?php

require_once __DIR__ . '/BaseDao.php';

class RentalDao extends BaseDao {
    public function __construct($table = "rentals") {
        parent::__construct($table);
    }

    public function getUnavailableDates($propertyId) {
        $stmt = $this->connection->prepare(
            "SELECT start_date, end_date FROM rentals WHERE property_id = :propertyId"
        );
        $stmt->bindParam(':propertyId', $propertyId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function isDateConflict($propertyId, $newStartDate, $newEndDate) {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM rentals 
             WHERE property_id = :propertyId 
             AND (start_date <= :newEndDate AND end_date >= :newStartDate)"
        );
        $stmt->bindParam(':propertyId', $propertyId);
        $stmt->bindParam(':newStartDate', $newStartDate);
        $stmt->bindParam(':newEndDate', $newEndDate);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getByUserId($userId) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM rentals WHERE user_id = :userId"
        );
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>