<?php
require_once "BaseDao.php";

class ReviewDao extends BaseDao {
    public function __construct($table = "reviews") {
        parent::__construct($table);
    }

    public function getByPropertyId($propertyId, $page = 1) {
        $limit = 4;
        $offset = ($page - 1) * $limit;

        $stmt = $this->connection->prepare(
            "SELECT * FROM reviews 
             WHERE property_id = :propertyId 
             ORDER BY rating DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM reviews WHERE property_id = :propertyId"
        );
        $countStmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return [
            'data' => $reviews,
            'pagination' => [
                'total_items' => (int)$total,
                'current_page' => $page,
                'items_per_page' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }

    public function getAverageRating($propertyId) {
        $stmt = $this->connection->prepare(
            "SELECT AVG(rating) as avg_rating FROM reviews WHERE property_id = :propertyId"
        );
        $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (float)$result['avg_rating'] : null;
    }
}

?>