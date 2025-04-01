<?php
require_once 'BaseDao.php';

class PropertyImagesDao extends BaseDao {
    public function __construct($table = "property_images") {
        parent::__construct($table);
    }

    public function getByPropertyId($propertyId) {
        $stmt = $this->connection->prepare("SELECT * FROM property_images WHERE property_id = :propertyId");
        $stmt->bindParam(':propertyId', $propertyId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}


?>