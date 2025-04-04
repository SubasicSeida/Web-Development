<?php
require_once "BaseDao.php";

class PropertyDao extends BaseDao {
    public function __construct($table = "properties") {
        parent::__construct($table);
    }

    public function getByTitle($title) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE title = :title");
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPriceRange($minPrice, $maxPrice) {
        $stmt = $this->connection->prepare("SELECT * FROM properties WHERE price BETWEEN :minPrice AND :maxPrice");
        $stmt->bindParam(':minPrice', $minPrice);
        $stmt->bindParam(':maxPrice', $maxPrice);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPropertyType($propertyType) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE property_type = :propertyType");
        $stmt->bindParam(':propertyType', $propertyType);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBySqft($minSqft, $maxSqft) {
        $stmt = $this->connection->prepare("SELECT * FROM properties WHERE sqft BETWEEN :minSqft AND :maxSqft");
        $stmt->bindParam(':minSqft', $minSqft);
        $stmt->bindParam(':maxSqft', $maxSqft);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCity($city) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE city = :city");
        $stmt->bindParam(':city', $city);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByListingType($listingType) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE listing_type = :listingType");
        $stmt->bindParam(':listingType', $listingType);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByBedroomCount($bedrooms) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE bedrooms = :bedrooms");
        $stmt->bindParam(':bedrooms', $bedrooms);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByBathroomCount($bathrooms) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE bathrooms = :bathrooms");
        $stmt->bindParam(':bathrooms', $bathrooms);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByAgentId($agentId) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE agent_id = :agentId");
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByKeyword($keyword) {
        $stmt = $this->connection->prepare("SELECT * FROM properties WHERE title LIKE :keyword");
        $likeKeyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $likeKeyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}

?>