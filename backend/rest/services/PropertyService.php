<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PropertyDao.php';

class PropertyService extends BaseService {
    public function __construct() {
        $dao = new PropertyDao();
        parent::__construct($dao);
    }

    public function getByAgentId($id, $page = 1) {
        return $this->dao->getByAgentId($id, $page);
    }

    public function searchProperties($filters = []) {
        if (!empty($filters['keyword']) && is_string($filters['keyword'])) {
            $filters['keyword'] = trim($filters['keyword']);
        }

        $allowedPropertyTypes = ['apartment', 'villa', 'studio', 'townhouse', 'commercial'];
        if (!empty($filters['propertyType'])) {
            $type = strtolower(trim($filters['propertyType']));
            if (!in_array($type, $allowedPropertyTypes, true)) {
                throw new Exception("Selected property type is not valid");
            }
            $filters['propertyType'] = $type;
        }

        $allowedListingTypes = ['sale', 'rent'];
        if (!empty($filters['listingType'])) {
            $type = strtolower(trim($filters['listingType']));
            if (!in_array($type, $allowedListingTypes, true)) {
                throw new Exception("Selected listing type is not valid");
            }
            $filters['listingType'] = $type;
        }

        if (isset($filters['minPrice'])) {
            if (!is_numeric($filters['minPrice']) || $filters['minPrice'] < 0) {
                throw new Exception("Minimum price must be a non-negative number.");
            }
            $filters['minPrice'] = (float)$filters['minPrice'];
        }

        if (isset($filters['maxPrice'])) {
            if (!is_numeric($filters['maxPrice']) || $filters['maxPrice'] < 0) {
                throw new Exception("Maximum price must be a non-negative number.");
            }
            $filters['maxPrice'] = (float)$filters['maxPrice'];
        }

        if (isset($filters['minPrice'], $filters['maxPrice']) && $filters['minPrice'] > $filters['maxPrice']) {
            throw new Exception("Minimum price cannot be greater than maximum price.");
        }

        if (isset($filters['minSize'])) {
            if (!is_numeric($filters['minSize']) || $filters['minSize'] < 0) {
                throw new Exception("Minimum size must be a non-negative number.");
            }
            $filters['minSize'] = (float)$filters['minSize'];
        }

        if (isset($filters['maxSize'])) {
            if (!is_numeric($filters['maxSize']) || $filters['maxSize'] < 0) {
                throw new Exception("Maximum size must be a non-negative number.");
            }
            $filters['maxSize'] = (float)$filters['maxSize'];
        }

        if (isset($filters['minSize'], $filters['maxSize']) && $filters['minSize'] > $filters['maxSize']) {
            throw new Exception("Minimum size cannot be greater than maximum size.");
        }

        if (isset($filters['bedrooms'])) {
            if (!is_numeric($filters['bedrooms']) || $filters['bedrooms'] < 0) {
                throw new Exception("Number of bedrooms must be a non-negative number.");
            }
            $filters['bedrooms'] = (int)$filters['bedrooms'];
        }

        if (isset($filters['bathrooms'])) {
            if (!is_numeric($filters['bathrooms']) || $filters['bathrooms'] < 0) {
                throw new Exception("Number of bathrooms must be a non-negative number.");
            }
            $filters['bathrooms'] = (int)$filters['bathrooms'];
        }

        $validSortFields = ['price', 'created_at'];
        $sortField = in_array($filters['sort'] ?? '', $validSortFields) ? $filters['sort'] : 'created_at';
        $filters['sort'] = $sortField;

        $order = strtolower($filters['order'] ?? 'desc');
        $sortOrder = in_array($order, ['asc', 'desc']) ? strtoupper($order) : 'DESC';
        $filters['order'] = $sortOrder;
        
        try {
            return $this->dao->search($filters);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to search properties.");
        }
    }
}

?>