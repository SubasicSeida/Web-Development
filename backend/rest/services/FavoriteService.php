<?php

require_once __DIR__ . '/../dao/FavoriteDao.php';
require_once __DIR__ . '/BaseService.php';

class FavoriteService extends BaseService {
    public function __construct() {
        $dao = new FavoritesDao();
        parent::__construct($dao);
    }

    public function getFavoritesByUserId($id, $page = 1) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid user ID.");
        }

        $page = is_numeric($page) && $page > 0 ? (int)$page : 1;

        return $this->dao->getFavoritesByUserId((int)$id, $page);
    }

    public function isFavorited($userId, $propertyId) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new Exception("Invalid user ID.");
        }

        if (!is_numeric($propertyId) || $propertyId <= 0) {
            throw new Exception("Invalid property ID.");
        }

        return $this->dao->isFavorited((int)$userId, (int)$propertyId);
    }
}

?>