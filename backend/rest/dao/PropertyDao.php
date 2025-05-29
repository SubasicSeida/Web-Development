<?php
require_once __DIR__ . "/BaseDao.php";

class PropertyDao extends BaseDao {
    protected $table;

    public function __construct() {
        $this->table = "properties";
        parent::__construct($this->table);
    }

    public function getByAgentId($agentId, $page = 1) {
        $limit = 3;
        $offset = ($page - 1) * $limit; 

        $stmt = $this->connection->prepare("SELECT * FROM  properties WHERE agent_id = :agentId LIMIT $limit OFFSET $offset");
        $stmt->bindParam(':agentId', $agentId, PDO::PARAM_INT);
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
            $keywords = preg_split('/\s+/', trim($filters['keyword']));
            $keywordClauses = [];

            foreach ($keywords as $index => $word) {
                $paramKey = ":keyword$index";
                $likeClause = "(title LIKE $paramKey OR description LIKE $paramKey OR address LIKE $paramKey OR city LIKE $paramKey)";
                $keywordClauses[] = $likeClause;
                $params[$paramKey] = '%' . $word . '%';
            }

            if (!empty($keywordClauses)) {
                $baseQuery .= " AND (" . implode(" AND ", $keywordClauses) . ")";
            }
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

        if (!empty($filters['minSize'])) {
            $baseQuery .= " AND sqft >= :minSize";
            $params[':minSize'] = (float)$filters['minSize'];
        }
    
        if (!empty($filters['maxSize'])) {
            $baseQuery .= " AND sqft <= :maxSize";
            $params[':maxSize'] = (float)$filters['maxSize'];
        }

        if (!empty($filters['bedrooms'])) {
            $baseQuery .= " AND bedrooms = :bedrooms";
            $params[':bedrooms'] = (float)$filters['bedrooms'];
        }

        if (!empty($filters['bathrooms'])) {
            $baseQuery .= " AND bathrooms = :bathrooms";
            $params[':bathrooms'] = (float)$filters['bathrooms'];
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
        $sortField = $filters['sort'];
        $sortOrder = $filters['order'];
        $dataQuery .= " ORDER BY {$sortField} {$sortOrder}";
    
        // pagination
        $limit = 9;
        $page = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $offset = ($page - 1) * $limit;
        $dataQuery .= " LIMIT $limit OFFSET $offset";
    
        $dataStmt = $this->connection->prepare($dataQuery);
        foreach ($params as $key => $value) {
            $dataStmt->bindValue($key, $value);
        }
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