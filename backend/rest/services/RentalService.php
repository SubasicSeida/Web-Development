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

    public function createRental($rentalData) {
        $startDate = $rentalData['start_date'];
        $endDate = $rentalData['end_date'];
        $propertyId = $rentalData['property_id'];

        if (!$this->validateDate($startDate) || !$this->validateDate($endDate)) {
            throw new Exception("Invalid date format.");
        }
    
        if ($startDate >= $endDate) {
            throw new Exception("Start date must be before end date.");
        }

        if ($this->dao->isDateConflict($propertyId, $startDate, $endDate)) {
            throw new Exception("The selected dates conflict with existing bookings.");
        }

        return $this->create($rentalData);
    }

    private function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function getByUserId($userId) {
        return $this->dao->getByUserId($userId);
    }
}

?>