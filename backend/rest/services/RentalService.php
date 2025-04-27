<?php

require_once 'BaseService.php';
require_once '../dao/RentalDao.php';

class RentalService extends BaseService {
    public function __construct() {
        $dao = new RentalDao();
        parent::__construct($dao);
    }

    public function getUnavailableDates($property_id) {
        return $this->dao->getUnavailableDates($property_id);
    }
}

?>