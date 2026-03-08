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
        
        $this->data['reviewsByCompany'] = $reviewsByCompany;
        $this->data['pageTitle'] = 'All Reviews';
        return $this->load->view('review/index');
    }

    public function company($id = null)
    {
        if (!empty($id)) {
            $this->companySingle($id);
            return;
        }

        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        
        $companies = $this->model->CompanyModel->getAll([], 'name ASC');
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        $this->data['companies'] = $companies;
        $this->data['pageTitle'] = 'Companies';
        $this->data['currentPage'] = 'companies';
        return $this->load->view('review/company');
    }

    private function companySingle($id)
    {
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        $this->load->model('UserModel');
        $this->load->model('CompanyTagModel');
        
        $company = $this->model->CompanyModel->get($id);
        
        if (!$company) {
            header('Location: /review/company');
            exit;
        }
        
        $reviews = $this->model->ReviewModel->getByCompany($id);
        $ratingInfo = $this->model->ReviewModel->getAverageRating($id);
        $tags = $this->model->CompanyTagModel->getTagsByCompany($id);
        
        foreach ($reviews as &$review) {
            if ($review['user_id']) {
                $user = $this->model->UserModel->get($review['user_id']);
                $review['user'] = $user;
            }
        }
        
        $this->data['company'] = $company;
        $this->data['reviews'] = $reviews;
        $this->data['tags'] = $tags;
        $this->data['currentPage'] = 'company';
        $this->data['average_rating'] = $ratingInfo['average_rating'] ?? 0;
        $this->data['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        $this->data['pageTitle'] = htmlspecialchars($company['name']);
        return $this->load->view('review/company_single');
    }

    public function search($query = null)
    {
        $post = $this->getPostData();
        $tagId = $_GET['tag'] ?? null;
        
        if (empty($query)) {
            $query = $post['query'] ?? '';
        }
        
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        $this->load->model('CompanyTagModel');
        $this->load->model('TagModel');
        
        if (!empty($tagId)) {
            $companies = $this->model->CompanyTagModel->getCompaniesByTag($tagId);
        } elseif (!empty($query)) {
            $companies = $this->model->CompanyModel->search($query);
        } else {
            header('Location: /review/company');
            exit;
        }
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        $allTags = $this->model->TagModel->getAllOrdered();
        
        $this->data['companies'] = $companies;
        $this->data['query'] = htmlspecialchars($query);
        $this->data['tags'] = $allTags;
        $this->data['selectedTag'] = $tagId;
        $this->data['pageTitle'] = 'Search Results';
        return $this->load->view('review/search');
    }
}

