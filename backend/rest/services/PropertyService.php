<?php

require_once 'BaseService.php';
require_once '../dao/PropertyDao.php';

class PropertyService extends BaseService {
    public function __construct() {
        $dao = new PropertyDao();
        parent::__construct($dao);
    }

    public function getByAgentId($id, $page = 1) {
        return $this->dao->getByAgentId($id, $page);
    }

    public function searchProperties($filters = []) {
        if (isset($filters['minPrice']) && isset($filters['maxPrice'])) {
            if ($filters['minPrice'] > $filters['maxPrice']) {
                throw new Exception("Minimum price cannot be greater than maximum price");
            }
        }

        try {
            return $this->dao->search($filters);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to search properties.");
        }
    }
}

?>