<?php

require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/BaseService.php';

class ReviewService extends BaseService {
    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function getByPropertyId($id, $page = 1) {
        if (!is_numeric($propertyId) || $propertyId <= 0) {
            throw new Exception("Invalid property ID.");
        }

        return $this->dao->getByPropertyId($propertyId, $page);
    }

    public function getAverageRating($propertyId) {
        if (!is_numeric($propertyId) || $propertyId <= 0) {
            throw new Exception("Invalid property ID.");
        }

        return $this->dao->getAverageRating($propertyId);
    }

    public function createReview($data) {
        if (empty($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new Exception("Invalid or missing user ID.");
        }
    
        if (empty($data['property_id']) || !is_numeric($data['property_id'])) {
            throw new Exception("Invalid or missing property ID.");
        }
    
        if (empty($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new Exception("Rating must be between 1 and 5.");
        }
    
        if (empty($data['comment'])) {
            $data['comment'] = '';
        }
    
        return $this->dao->createReview($data);
    }

    public function deleteReviewByUserId($userId, $reviewId) {
        if (!is_numeric($userId) || !is_numeric($reviewId)) {
            throw new Exception("Invalid IDs.");
        }

        $result = $this->dao->deleteReviewByUserId($userId, $reviewId);
        if ($result === 0) {
            throw new Exception("Review not found or not owned by user.");
        }

        return true;
    }

    public function deleteAnyReview($reviewId) {
        if (!is_numeric($reviewId) || $reviewId <= 0) {
            throw new Exception("Invalid review ID.");
        }

        $deleted = parent::delete($reviewId);
        if ($deleted === 0) {
            throw new Exception("Review not found.");
        }

        return true;
    }
    
}

?>