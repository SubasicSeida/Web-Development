<?php
require_once "BaseDao.php";

class PropertyDao extends BaseDao {
    public function __construct($table = "properties") {
        parent::__construct($table);
    }

    public function getByAgentId($agentId) {
        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE agent_id = :agentId");
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($filters = []) {
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM properties WHERE 1=1";
        $params = [];
        
        if (!empty($filters['keyword'])) {
            $query .= " AND (title LIKE :keyword OR description LIKE :keyword 
            OR address LIKE :keyword OR city LIKE :keyword)";
            $params[':keyword'] = "%" . $filters['keyword'] . "%";
        }
        
        if (!empty($filters['propertyType'])) {
            $query .= " AND property_type = :propertyType";
            $params[':propertyType'] = $filters['propertyType'];
        }
        
        if (!empty($filters['listingType'])) {
            $query .= " AND listing_type = :listingType";
            $params[':listingType'] = $filters['listingType'];
        }
        
        
        if (!empty($filters['minPrice'])) {
            $query .= " AND price >= :minPrice";
            $params[':minPrice'] = (float)$filters['minPrice'];
        }
        if (!empty($filters['maxPrice'])) {
            $query .= " AND price <= :maxPrice";
            $params[':maxPrice'] = (float)$filters['maxPrice'];
        }
    
        
        $validSortFields = ['price', 'created_at'];
        $sortField = in_array($filters['sort'] ?? null, $validSortFields) ? $filters['sort'] : 'created_at';
        $sortOrder = strtoupper($filters['order'] ?? '') === 'ASC' ? 'ASC' : 'DESC';
        $query .= " ORDER BY {$sortField} {$sortOrder}";
    
        
        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 9;
        $page = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $offset = ($page - 1) * $limit;
        
        $query .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
    
        
        $stmt = $this->connection->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $properties = $stmt->fetchAll();
    

        $total = $this->connection->query("SELECT FOUND_ROWS()")->fetchColumn();
    
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