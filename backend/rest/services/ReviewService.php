<?php

require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/BaseService.php';

class ReviewService extends BaseService {
    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function getByPropertyId($id, $page = 1) {
        if (!is_numeric($propertyId) || $propertyId <= 0) {
            throw new Exception("Invalid property ID.");
        }

        $page = is_numeric($page) && $page > 0 ? (int)$page : 1;

        return $this->dao->getByPropertyId((int)$propertyId, $page);
    }

    public function getAverageRating($propertyId) {
        if (!is_numeric($propertyId) || $propertyId <= 0) {
            throw new Exception("Invalid property ID.");
        }

        return $this->dao->getAverageRating((int)$propertyId);
    }
}

?>