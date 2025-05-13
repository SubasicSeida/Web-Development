<?php
require_once __DIR__ . "/BaseDao.php";

class ReviewDao extends BaseDao {
    protected $table;

    public function __construct() {
        $this->table = "reviews";
        parent::__construct($table);
    }

    public function getByPropertyId($propertyId, $page = 1) {
        $limit = 4;
        $offset = ($page - 1) * $limit;

        $stmt = $this->connection->prepare(
            "SELECT * FROM reviews 
             WHERE property_id = :propertyId 
             ORDER BY rating DESC
             LIMIT $limit OFFSET $offset"
        );
        $stmt->bindParam(':propertyId', $propertyId, PDO::PARAM_INT);
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

    public function createReview($data) {
        $stmt = $this->connection->prepare("INSERT INTO reviews (user_id, property_id, rating, comment, created_at) VALUES (:user_id, :property_id, :rating, :comment, NOW())");
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':property_id', $data['property_id'], PDO::PARAM_INT);
        $stmt->bindParam(':comment', $data['comment']);
        $stmt->bindParam(':rating', $data['rating']);

        $stmt->execute();
        return $this->connection->lastInsertId();
    }
}

?>