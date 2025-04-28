<?php

require_once '../dao/PropertyImagesDao.php';
require_once 'BaseService.php';

class PropertyImagesService extends BaseService {
    public function __construct() {
        $dao = new PropertyImagesDao();
        parent::__construct($dao);
    }

    public function getByPropertyId($id) {
        $images = $this->dao->getByPropertyId($id);

        if(!empty($images)){
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