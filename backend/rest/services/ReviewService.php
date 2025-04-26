<?php

require_once '../dao/ReviewDao.php';
require_once 'BaseService.php';

class ReviewService extends BaseService {
    public function __constructor() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    /* public function getByUserId($id) {
        return $this->dao->getByUserId($id);
    } */

    public function getByPropertyId($id) {
        return $this->dao->getByPropertyId($id);
    }

    public function getByRating($rating) {
        return $this->dao->getByRating($rating);
    }
}

?>