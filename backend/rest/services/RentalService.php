<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/RentalDao.php';

class RentalService extends BaseService {
    public function __construct() {
        $dao = new RentalDao();
        parent::__construct($dao);
    }

    public function getUnavailableDates($property_id) {
        if (!is_numeric($property_id) || $property_id <= 0) {
            throw new Exception("Invalid property ID.");
        }

        return $this->dao->getUnavailableDates($property_id);
    }

    public function createRental($rentalData) {
        if (empty($rentalData['start_date']) || empty($rentalData['end_date']) || empty($rentalData['property_id'])) {
            throw new Exception("Missing required rental fields.");
        }

        $startDate = trim($rentalData['start_date']);
        $endDate = trim($rentalData['end_date']);
        $propertyId = (int) $rentalData['property_id'];

        if (!is_numeric($propertyId) || $propertyId <= 0) {
            throw new Exception("Invalid property ID.");
        }

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
        if (!is_numeric($userId) || $userId <= 0) {
            throw new Exception("Invalid user ID.");
        }
        
        return $this->dao->getByUserId($userId);
    }
}

?>