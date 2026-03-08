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
        'deleteReview' => ["POST"],
        'createCompany' => ["POST"],
        'updateCompany' => ["POST"],
        'deleteCompany' => ["POST"],
        'createTag' => ["POST"],
        'updateTag' => ["POST"],
        'deleteTag' => ["POST"],
    ];

    public $adminMethods = [
        'deleteReview',
        'createCompany',
        'updateCompany',
        'deleteCompany',
        'createTag',
        'updateTag',
        'deleteTag',
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
        $this->load->model('TagModel');
        $tags = $this->model->TagModel->getAllOrdered();
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

    public function deleteReview()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('ReviewModel');
        $review = $this->model->ReviewModel->get($data['id']);
        
        if (!$review) {
            ResponceApi::returnData(['error' => 'Review not found'], 404);
        }
        
        try {
            $this->model->ReviewModel->delete($data['id']);
            ResponceApi::returnData(['message' => 'Review deleted successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to delete review'], 400);
        }
    }

    public function createCompany()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['name'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('CompanyModel');
        $this->load->model('CompanyTagModel');
        
        $name = trim($data['name']);
        $description = trim($data['description'] ?? '');
        $website = trim($data['website'] ?? '');
        $slug = trim($data['slug'] ?? '');
        $tagIds = $data['tags'] ?? [];
        
        if (empty($name)) {
            ResponceApi::returnData(['error' => 'Company name is required'], 400);
        }
        
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
            $slug = trim($slug, '-');
        }
        
        $existing = $this->model->CompanyModel->findBySlug($slug);
        if ($existing) {
            ResponceApi::returnData(['error' => 'A company with this slug already exists'], 400);
        }
        
        $token = ValidationApi::getToken();
        $tokenData = ValidationApi::decryptToken($token);
        $createdBy = $tokenData['id'] ?? null;
        
        try {
            $companyData = [
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
                'website' => $website,
                'created_by' => $createdBy
            ];
            
            $this->model->CompanyModel->add($companyData);
            $company = $this->model->CompanyModel->findBySlug($slug);
            
            if ($company && !empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $this->model->CompanyTagModel->addTagToCompany($company['id'], $tagId);
                }
            }
            
            ResponceApi::returnData(['message' => 'Company created successfully', 'company' => $company], 201);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to create company'], 400);
        }
    }

    public function updateCompany()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id', 'name'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('CompanyModel');
        $this->load->model('TagModel');
        $this->load->model('CompanyTagModel');
        
        $company = $this->model->CompanyModel->get($data['id']);
        if (!$company) {
            ResponceApi::returnData(['error' => 'Company not found'], 404);
        }
        
        $name = trim($data['name']);
        $description = trim($data['description'] ?? '');
        $website = trim($data['website'] ?? '');
        $slug = trim($data['slug'] ?? '');
        $tagIds = $data['tags'] ?? [];
        
        if (empty($name)) {
            ResponceApi::returnData(['error' => 'Company name is required'], 400);
        }
        
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
            $slug = trim($slug, '-');
        }
        
        $existing = $this->model->CompanyModel->findBySlug($slug);
        if ($existing && $existing['id'] != $data['id']) {
            ResponceApi::returnData(['error' => 'A company with this slug already exists'], 400);
        }
        
        try {
            $updateData = [
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
                'website' => $website
            ];
            
            $this->model->CompanyModel->update($data['id'], $updateData);
            
            $currentTags = $this->model->CompanyTagModel->getTagsByCompany($data['id']);
            $currentTagIds = array_column($currentTags, 'id');
            
            foreach ($tagIds as $tagId) {
                if (!in_array($tagId, $currentTagIds)) {
                    $this->model->CompanyTagModel->addTagToCompany($data['id'], $tagId);
                }
            }
            
            foreach ($currentTagIds as $tagId) {
                if (!in_array($tagId, $tagIds)) {
                    $this->model->CompanyTagModel->removeTagFromCompany($data['id'], $tagId);
                }
            }
            
            ResponceApi::returnData(['message' => 'Company updated successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to update company'], 400);
        }
    }

    public function deleteCompany()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('CompanyModel');
        $company = $this->model->CompanyModel->get($data['id']);
        
        if (!$company) {
            ResponceApi::returnData(['error' => 'Company not found'], 404);
        }
        
        try {
            $this->model->CompanyModel->delete($data['id']);
            ResponceApi::returnData(['message' => 'Company deleted successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to delete company'], 400);
        }
    }

    public function createTag()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['name'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('TagModel');
        
        $name = trim($data['name']);
        $color = trim($data['color'] ?? '#3b82f6');
        $description = trim($data['description'] ?? '');
        
        if (empty($name)) {
            ResponceApi::returnData(['error' => 'Tag name is required'], 400);
        }
        
        $existing = $this->model->TagModel->findByName($name);
        if ($existing) {
            ResponceApi::returnData(['error' => 'A tag with this name already exists'], 400);
        }
        
        try {
            $tagData = [
                'name' => $name,
                'color' => $color,
                'description' => $description
            ];
            
            $this->model->TagModel->add($tagData);
            $tag = $this->model->TagModel->findByName($name);
            
            ResponceApi::returnData(['message' => 'Tag created successfully', 'tag' => $tag], 201);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to create tag'], 400);
        }
    }

    public function updateTag()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id', 'name'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('TagModel');
        
        $tag = $this->model->TagModel->get($data['id']);
        if (!$tag) {
            ResponceApi::returnData(['error' => 'Tag not found'], 404);
        }
        
        $name = trim($data['name']);
        $color = trim($data['color'] ?? '#3b82f6');
        $description = trim($data['description'] ?? '');
        
        if (empty($name)) {
            ResponceApi::returnData(['error' => 'Tag name is required'], 400);
        }
        
        $existing = $this->model->TagModel->findByName($name);
        if ($existing && $existing['id'] != $data['id']) {
            ResponceApi::returnData(['error' => 'A tag with this name already exists'], 400);
        }
        
        try {
            $updateData = [
                'name' => $name,
                'color' => $color,
                'description' => $description
            ];
            
            $this->model->TagModel->update($data['id'], $updateData);
            
            ResponceApi::returnData(['message' => 'Tag updated successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to update tag'], 400);
        }
    }

    public function deleteTag()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id'];
        $this->validateFields($requiredFields, $data);
        
        $this->load->model('TagModel');
        $tag = $this->model->TagModel->get($data['id']);
        
        if (!$tag) {
            ResponceApi::returnData(['error' => 'Tag not found'], 404);
        }
        
        try {
            $this->model->TagModel->delete($data['id']);
            ResponceApi::returnData(['message' => 'Tag deleted successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to delete tag'], 400);
        }
    }
}