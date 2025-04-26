<?php

require_once '../dao/FavoritesDao.php';
require_once 'BaseService.php';

class FavoritesService extends BaseService {
    public function __construct() {
        $dao = new FavoritesDao();
        parent::__construct($dao);
    }

    public function getByUserId($id) {
        return $this->dao->getByUserId($id);
    }
}

?>