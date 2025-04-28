<?php

require_once '../dao/FavoritesDao.php';
require_once 'BaseService.php';

class FavoritesService extends BaseService {
    public function __construct() {
        $dao = new FavoritesDao();
        parent::__construct($dao);
    }

    public function getFavoritesByUserId($id, $page = 1) {
        return $this->dao->getFavoritesByUserId($id, $page);
    }

    public function isFavorited($userId, $propertyId) {
        return $this->dao->isFavorited($userId, $propertyId);
    }
}

?>