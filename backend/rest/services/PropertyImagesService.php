<?php

require_once '../dao/PropertyImagesDao.php';
require_once 'BaseService.php';

class PropertyImagesService extends BaseService {
    public function __construct() {
        $dao = new PropertyImagesDao();
        parent::__construct($dao);
    }

    public function getByPropertyId($id) {
        return $this->dao->getByPropertyId($id);
    }
}

?>