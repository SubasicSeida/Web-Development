<?php

require_once 'BaseDao.php';

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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>