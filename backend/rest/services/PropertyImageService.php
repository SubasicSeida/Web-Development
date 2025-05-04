<?php

require_once __DIR__ . '/../dao/PropertyImageDao.php';
require_once __DIR__ . '/BaseService.php';

class PropertyImageService extends BaseService {
    public function __construct() {
        $dao = new PropertyImagesDao();
        parent::__construct($dao);
    }

    public function getByPropertyId($id) {
        if (!is_numeric($id) || (int)$id <= 0) {
            throw new Exception("Invalid property ID.");
        }

        $id = (int)$id;
        $images = $this->dao->getByPropertyId($id);

        if (!empty($images)) {
            return $images;
        } else {
            return [[
                'id' => 0,
                'property_id' => $id,
                'image_url' => 'default.jpg'
            ]];
        }
    }
}

?>