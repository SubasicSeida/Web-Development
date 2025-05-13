<?php
require_once __DIR__ . '/BaseDao.php';

class PropertyImagesDao extends BaseDao {
    protected $table;

    public function __construct() {
        $this->table = "property_images";
        parent::__construct($this->table);
    }

    public function getByPropertyId($propertyId) {
        $stmt = $this->connection->prepare("SELECT * FROM property_images WHERE property_id = :propertyId");
        $stmt->bindParam(':propertyId', $propertyId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}


?>