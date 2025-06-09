<?php

require_once __DIR__ . '/../dao/FavoriteDao.php';
require_once __DIR__ . '/BaseService.php';

class FavoriteService extends BaseService {
    public function __construct() {
        $dao = new FavoritesDao();
        parent::__construct($dao);
    }

    public function getPaginatedFavorites($id, $page = 1) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid user ID.");
        }

        $page = is_numeric($page) && $page > 0 ? (int)$page : 1;

        return $this->dao->getPaginatedFavorites((int)$id, $page);
    }

    public function getFavoritesByUserId($userId) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new Exception("Invalid user ID.");
        }

        return $this->dao->getFavoritesByUserId($userId);
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

    public function createFavorite($userId, $propertyId) {
        if (!is_numeric($userId) || !is_numeric($propertyId)) {
            throw new InvalidArgumentException("Invalid IDs.");
        }

        return parent::create([
            'user_id' => $userId,
            'property_id' => $propertyId
        ]);
    }

    public function removeFavorite($userId, $propertyId) {
        if (!is_numeric($userId) || !is_numeric($propertyId)) {
            throw new InvalidArgumentException("Invalid IDs.");
        }

        return $this->dao->removeFavorite($userId, $propertyId);
    }
}

?>