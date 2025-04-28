<?php

require_once '../dao/ReviewDao.php';
require_once 'BaseService.php';

class ReviewService extends BaseService {
    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function getByPropertyId($id, $page = 1) {
        return $this->dao->getByPropertyId($id, $page);
    }

    public function getAverageRating($propertyId) {
        return $this->dao->getAverageRating($propertyId);
    }
}

?>