<?php 

class ReviewApi extends ControllerApi
{
    public $requestRules = [
        'getCompany' => ["POST"],
        'listCompany' => ["GET"],
        'postReview' => ["POST"],
        'getReview' => ["GET"],
        'getTags' => ["GET"],
        'searchByCompany' => ["POST"],
        'searchByTag' => ["POST"],
    ];

    public $adminMethods = [

    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getCompany()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        $this->load->model('UserModel');
        $this->load->model('CompanyTagModel');
        
        $company = $this->model->CompanyModel->get($data['id']);
        
        if (!$company) {
            ResponceApi::returnData(['error' => 'Company not found'], 404);
        }
        
        $reviews = $this->model->ReviewModel->getByCompany($data['id']);
        $ratingInfo = $this->model->ReviewModel->getAverageRating($data['id']);
        $tags = $this->model->CompanyTagModel->getTagsByCompany($data['id']);
        
        foreach ($reviews as &$review) {
            if ($review['user_id']) {
                $user = $this->model->UserModel->get($review['user_id']);
                $review['user'] = $user;
            }
        }
        
        ResponceApi::returnData([
            'company' => $company,
            'reviews' => $reviews,
            'tags' => $tags,
            'average_rating' => $ratingInfo['average_rating'] ?? 0,
            'total_reviews' => $ratingInfo['total_reviews'] ?? 0
        ]);
    }

    public function listCompany()
    {
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        
        $companies = $this->model->CompanyModel->getAll([], 'name ASC');
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        ResponceApi::returnData(['companies' => $companies]);
    }

    public function postReview()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['company_id', 'rating', 'title', 'content'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('ReviewModel');
        $this->load->model('CompanyModel');
        
        $company = $this->model->CompanyModel->get($data['company_id']);
        if (!$company) {
            ResponceApi::returnData(['error' => 'Company not found'], 404);
        }
        
        $rating = intval($data['rating']);
        if ($rating < 1 || $rating > 5) {
            ResponceApi::returnData(['error' => 'Rating must be between 1 and 5'], 400);
        }
        
        $userId = null;
        $token = ValidationApi::getToken();
        if (!empty($token)) {
            $tokenData = ValidationApi::decryptToken($token);
            if ($tokenData && isset($tokenData['id'])) {
                $userId = $tokenData['id'];
            }
        }
        
        try {
            $reviewData = [
                'company_id' => $data['company_id'],
                'user_id' => $userId,
                'rating' => $rating,
                'title' => trim($data['title']),
                'content' => trim($data['content'])
            ];
            
            $this->model->ReviewModel->add($reviewData);
            
            ResponceApi::returnData(['message' => 'Review posted successfully'], 201);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to post review'], 400);
        }
    }

    public function getReview()
    {
        $this->load->model('ReviewModel');
        $this->load->model('CompanyModel');
        $this->load->model('UserModel');
        
        $allReviews = $this->model->ReviewModel->getAll([], 'created_at DESC');
        
        $reviewsByCompany = [];
        
        foreach ($allReviews as $review) {
            $companyId = $review['company_id'];
            
            if (!isset($reviewsByCompany[$companyId])) {
                $company = $this->model->CompanyModel->get($companyId);
                if ($company) {
                    $ratingInfo = $this->model->ReviewModel->getAverageRating($companyId);
                    $reviewsByCompany[$companyId] = [
                        'company' => $company,
                        'reviews' => [],
                        'average_rating' => $ratingInfo['average_rating'] ?? 0,
                        'total_reviews' => $ratingInfo['total_reviews'] ?? 0
                    ];
                }
            }
            
            if ($review['user_id']) {
                $user = $this->model->UserModel->get($review['user_id']);
                $review['user'] = $user;
            }
            
            $reviewsByCompany[$companyId]['reviews'][] = $review;
        }
        
        ResponceApi::returnData(['reviewsByCompany' => array_values($reviewsByCompany)]);
    }

    public function getTags()
    {
        $this->load->model('CompanyTagModel');
        $tags = $this->model->CompanyTagModel->getAll([], 'name ASC');
        ResponceApi::returnData(['tags' => $tags]);
    }

    public function searchByCompany()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['query'];
        $this->validateFields($requiredFields, $data);
        
        $query = trim($data['query']);
        
        if (empty($query)) {
            ResponceApi::returnData(['error' => 'Query cannot be empty'], 400);
        }
        
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        
        $companies = $this->model->CompanyModel->search($query);
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        ResponceApi::returnData(['companies' => $companies, 'query' => $query]);
    }

    public function searchByTag()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['tag_id'];
        $this->validateFields($requiredFields, $data);
        
        $tagId = intval($data['tag_id']);
        
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        $this->load->model('CompanyTagModel');
        
        $companies = $this->model->CompanyTagModel->getCompaniesByTag($tagId);
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        ResponceApi::returnData(['companies' => $companies, 'tag_id' => $tagId]);
    }
}