<?php

require_once 'BaseService.php';
require_once '../dao/PropertyDao.php';

class PropertyService extends BaseService {
    public function __construct() {
        $dao = new PropertyDao();
        parent::__construct($dao);
    }

    public function getByAgentId($id) {
        return $this->dao->getByAgentId($id);
    }

    public function searchProperties($filters = []) {
        if (isset($filters['minPrice']) && isset($filters['maxPrice'])) {
            if ($filters['minPrice'] > $filters['maxPrice']) {
                throw new Exception("Minimum price cannot be greater than maximum price");
            }
        }

        /*
        if (isset($filters['keyword'])) {
            $filters['title'] = $filters['keyword'];
            unset($filters['keyword']);
        }
        */

        return $this->dao->search($filters);
    }
}

?>