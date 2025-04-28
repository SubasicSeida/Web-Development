<?php
require_once "BaseDao.php";

class PropertyDao extends BaseDao {
    public function __construct($table = "properties") {
        parent::__construct($table);
    }

    public function getByAgentId($agentId, $page = 1) {
        $limit = 3;
        $offset = ($page - 1) * $limit; 

        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE agent_id = :agentId LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':agentId', $agentId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $agentProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM properties WHERE agent_id = :agentId");
        $countStmt->bindParam(':agentId', $agentId, PDO::PARAM_INT);
        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return [
            'data' => $agentProperties,
            'pagination' => [
                'total_items' => (int)$total,
                'current_page' => $page,
                'items_per_page' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }

    public function search($filters = []) {
        $baseQuery = "FROM properties WHERE 1=1";
        $params = [];
    
        if (!empty($filters['keyword'])) {
            $baseQuery .= " AND (title LIKE :keyword OR description LIKE :keyword 
                                OR address LIKE :keyword OR city LIKE :keyword)";
            $params[':keyword'] = "%" . $filters['keyword'] . "%";
        }
    
        if (!empty($filters['propertyType'])) {
            $baseQuery .= " AND property_type = :propertyType";
            $params[':propertyType'] = $filters['propertyType'];
        }
    
        if (!empty($filters['listingType'])) {
            $baseQuery .= " AND listing_type = :listingType";
            $params[':listingType'] = $filters['listingType'];
        }
    
        if (!empty($filters['minPrice'])) {
            $baseQuery .= " AND price >= :minPrice";
            $params[':minPrice'] = (float)$filters['minPrice'];
        }
    
        if (!empty($filters['maxPrice'])) {
            $baseQuery .= " AND price <= :maxPrice";
            $params[':maxPrice'] = (float)$filters['maxPrice'];
        }
    
        // count total items
        $countQuery = "SELECT COUNT(*) " . $baseQuery;
        $countStmt = $this->connection->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
    
        // fetch paginated data
        $dataQuery = "SELECT * " . $baseQuery;
    
        // sorting
        $validSortFields = ['price', 'created_at'];
        $sortField = in_array($filters['sort'] ?? null, $validSortFields) ? $filters['sort'] : 'created_at';
        $sortOrder = strtoupper($filters['order'] ?? '') === 'ASC' ? 'ASC' : 'DESC';
        $dataQuery .= " ORDER BY {$sortField} {$sortOrder}";
    
        // pagination
        $limit = 9;
        $page = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $offset = ($page - 1) * $limit;
        $dataQuery .= " LIMIT :limit OFFSET :offset";
    
        $dataStmt = $this->connection->prepare($dataQuery);
        foreach ($params as $key => $value) {
            $dataStmt->bindValue($key, $value);
        }
        $dataStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $dataStmt->execute();
        $properties = $dataStmt->fetchAll();
    
        return [
            'data' => $properties,
            'pagination' => [
                'total_items' => (int)$total,
                'current_page' => $page,
                'items_per_page' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }
     
    
}

?>