<?php
require_once 'BaseDao.php';

class FavoritesDao extends BaseDao {
    public function __construct($table = "favorites") {
        parent::__construct($table);
    }

    public function getFavoritesByUserId($userId, $page = 1) {
        $limit = 3;
        $offset = ($page - 1) * $limit;

        $stmt = $this->connection->prepare(
            "SELECT p.* 
             FROM favorites f
             JOIN properties p ON f.property_id = p.id
             WHERE f.user_id = :userId
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $this->connection->prepare(
            "SELECT COUNT(*) 
             FROM favorites f
             JOIN properties p ON f.property_id = p.id
             WHERE f.user_id = :userId"
        );
        $countStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return [
            'data' => $favorites,
            'pagination' => [
                'total_items' => (int)$total,
                'current_page' => $page,
                'items_per_page' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }

    public function isFavorited($userId, $propertyId) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :userId AND property_id = :propertyId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':propertyId', $propertyId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

?>