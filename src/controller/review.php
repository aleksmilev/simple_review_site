<?php

class Review extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['currentPage'] = 'reviews';
    }

    public function index()
    {
        $this->loadModel('ReviewModel');
        $this->loadModel('CompanyModel');
        $this->loadModel('UserModel');
        
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
        
        $this->data['reviewsByCompany'] = $reviewsByCompany;
        $this->data['pageTitle'] = 'All Reviews';
        $this->loadView('review/index');
    }

    public function company($id = null)
    {
        if (!empty($id)) {
            $this->companySingle($id);
            return;
        }

        $this->loadModel('CompanyModel');
        $this->loadModel('ReviewModel');
        
        $companies = $this->model->CompanyModel->getAll([], 'name ASC');
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        $this->data['companies'] = $companies;
        $this->data['pageTitle'] = 'Companies';
        $this->data['currentPage'] = 'companies';
        $this->loadView('review/company');
    }

    private function companySingle($id)
    {
        $post = $this->getPostData();
        
        if (!empty($post)) {
            
        }
        
        $this->loadModel('CompanyModel');
        $this->loadModel('ReviewModel');
        $this->loadModel('UserModel');
        
        $company = $this->model->CompanyModel->get($id);
        
        if (!$company) {
            header('Location: /review/company');
            exit;
        }
        
        $reviews = $this->model->ReviewModel->getByCompany($id);
        $ratingInfo = $this->model->ReviewModel->getAverageRating($id);
        
        foreach ($reviews as &$review) {
            if ($review['user_id']) {
                $user = $this->model->UserModel->get($review['user_id']);
                $review['user'] = $user;
            }
        }
        
        $this->data['company'] = $company;
        $this->data['reviews'] = $reviews;
        $this->data['currentPage'] = 'company';
        $this->data['average_rating'] = $ratingInfo['average_rating'] ?? 0;
        $this->data['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        $this->data['pageTitle'] = htmlspecialchars($company['name']);
        $this->loadView('review/company_single');
    }

    public function search($query = null)
    {
        if (empty($query)) {
            $post = $this->getPostData();
            $query = $post['query'] ?? '';
        }
        
        if (empty($query)) {
            header('Location: /review/company');
            exit;
        }
        
        $this->loadModel('CompanyModel');
        $this->loadModel('ReviewModel');
        
        $companies = $this->model->CompanyModel->search($query);
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        $this->data['companies'] = $companies;
        $this->data['query'] = htmlspecialchars($query);
        $this->data['pageTitle'] = 'Search Results';
        $this->loadView('review/search');
    }
}

