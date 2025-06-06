<?php

require_once __DIR__ . '/BaseDao.php';

class RentalDao extends BaseDao {
    protected $table;

    public function __construct() {
        $this->table = "rentals";
        parent::__construct($this->table);
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

    public function getRentalDetailsByPropertyId($propertyId) {
        $sql = "
        SELECT 
            r.id AS rental_id,
            r.property_id,
            r.user_id,
            r.start_date,
            r.end_date,
            r.created_at,
            u.first_name,
            u.last_name,
            u.email
        FROM rentals r
        JOIN users u ON r.user_id = u.id
        WHERE r.property_id = :property_id
        ORDER BY r.start_date ASC
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':property_id', $propertyId);
    $stmt->execute();
    return $stmt->fetchAll();
    }

}

?>