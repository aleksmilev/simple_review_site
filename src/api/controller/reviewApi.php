<?php 

class ReviewApi extends ControllerApi
{
    public $requestRules = [
        'getCompany' => ["POST"],
        'listCompany' => ["GET"],
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
}