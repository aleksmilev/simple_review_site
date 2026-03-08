<?php

class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: /user/login');
            exit;
        }
        
        $this->data['currentPage'] = 'admin';
    }

    public function index()
    {
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        $this->load->model('UserModel');
        $this->load->model('TagModel');
        $this->load->model('FeedbackModel');
        
        $totalCompanies = count($this->model->CompanyModel->getAll());
        $totalReviews = count($this->model->ReviewModel->getAll());
        $totalUsers = count($this->model->UserModel->getAll());
        $totalTags = count($this->model->TagModel->getAll());
        $recentFeedback = $this->model->FeedbackModel->getRecent(5);
        
        $this->data['totalCompanies'] = $totalCompanies;
        $this->data['totalReviews'] = $totalReviews;
        $this->data['totalUsers'] = $totalUsers;
        $this->data['totalTags'] = $totalTags;
        $this->data['recentFeedback'] = $recentFeedback;
        $this->data['pageTitle'] = 'Admin Dashboard';
        return $this->load->view('admin/index');
    }

    public function companies()
    {
        $this->load->model('CompanyModel');
        $this->load->model('ReviewModel');
        
        $post = $this->getPostData();
        
        if (!empty($post)) {
            if (isset($post['action']) && $post['action'] == 'delete' && isset($post['id'])) {
                try {
                    $this->model->CompanyModel->delete($post['id']);
                    $this->data['success'] = 'Company deleted successfully';
                } catch (Exception $e) {
                    $this->data['error'] = 'Failed to delete company';
                }
            }
        }
        
        $companies = $this->model->CompanyModel->getAll([], 'created_at DESC');
        
        foreach ($companies as &$company) {
            $ratingInfo = $this->model->ReviewModel->getAverageRating($company['id']);
            $company['average_rating'] = $ratingInfo['average_rating'] ?? 0;
            $company['total_reviews'] = $ratingInfo['total_reviews'] ?? 0;
        }
        
        $this->data['companies'] = $companies;
        $this->data['pageTitle'] = 'Manage Companies';
        return $this->load->view('admin/companies');
    }

    public function company($action = null, $id = null)
    {
        if ($action == 'create') {
            $this->companyCreate();
        } elseif ($action == 'edit' && $id) {
            $this->companyEdit($id);
        } else {
            header('Location: /admin/companies');
            exit;
        }
    }

    private function companyCreate()
    {
        $this->load->model('CompanyModel');
        $this->load->model('TagModel');
        
        $post = $this->getPostData();
        $errors = [];
        
        if (!empty($post)) {
            $name = trim($post['name'] ?? '');
            $description = trim($post['description'] ?? '');
            $website = trim($post['website'] ?? '');
            $slug = trim($post['slug'] ?? '');
            $tagIds = $post['tags'] ?? [];
            
            if (empty($name)) {
                $errors[] = 'Company name is required';
            }
            
            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
                $slug = trim($slug, '-');
            }
            
            if (empty($errors)) {
                $existing = $this->model->CompanyModel->findBySlug($slug);
                if ($existing) {
                    $errors[] = 'A company with this slug already exists';
                }
            }
            
            if (empty($errors)) {
                try {
                    $companyData = [
                        'name' => $name,
                        'description' => $description,
                        'slug' => $slug,
                        'website' => $website,
                        'created_by' => $_SESSION['user_id']
                    ];
                    
                    $this->model->CompanyModel->add($companyData);
                    $company = $this->model->CompanyModel->findBySlug($slug);
                    
                    if ($company && !empty($tagIds)) {
                        $this->load->model('CompanyTagModel');
                        foreach ($tagIds as $tagId) {
                            $this->model->CompanyTagModel->addTagToCompany($company['id'], $tagId);
                        }
                    }
                    
                    header('Location: /admin/companies');
                    exit;
                } catch (Exception $e) {
                    $errors[] = 'Failed to create company';
                }
            }
            
            $this->data['errors'] = $errors;
            $this->data['post'] = $post;
        }
        
        $tags = $this->model->TagModel->getAllOrdered();
        $this->data['tags'] = $tags;
        $this->data['pageTitle'] = 'Create Company';
        return $this->load->view('admin/company_form');
    }

    private function companyEdit($id)
    {
        $this->load->model('CompanyModel');
        $this->load->model('TagModel');
        $this->load->model('CompanyTagModel');
        
        $company = $this->model->CompanyModel->get($id);
        
        if (!$company) {
            header('Location: /admin/companies');
            exit;
        }
        
        $post = $this->getPostData();
        $errors = [];
        
        if (!empty($post)) {
            $name = trim($post['name'] ?? '');
            $description = trim($post['description'] ?? '');
            $website = trim($post['website'] ?? '');
            $slug = trim($post['slug'] ?? '');
            $tagIds = $post['tags'] ?? [];
            
            if (empty($name)) {
                $errors[] = 'Company name is required';
            }
            
            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
                $slug = trim($slug, '-');
            }
            
            if (empty($errors)) {
                $existing = $this->model->CompanyModel->findBySlug($slug);
                if ($existing && $existing['id'] != $id) {
                    $errors[] = 'A company with this slug already exists';
                }
            }
            
            if (empty($errors)) {
                try {
                    $updateData = [
                        'name' => $name,
                        'description' => $description,
                        'slug' => $slug,
                        'website' => $website
                    ];
                    
                    $this->model->CompanyModel->update($id, $updateData);
                    
                    $currentTags = $this->model->CompanyTagModel->getTagsByCompany($id);
                    $currentTagIds = array_column($currentTags, 'id');
                    
                    foreach ($tagIds as $tagId) {
                        if (!in_array($tagId, $currentTagIds)) {
                            $this->model->CompanyTagModel->addTagToCompany($id, $tagId);
                        }
                    }
                    
                    foreach ($currentTagIds as $tagId) {
                        if (!in_array($tagId, $tagIds)) {
                            $this->model->CompanyTagModel->removeTagFromCompany($id, $tagId);
                        }
                    }
                    
                    header('Location: /admin/companies');
                    exit;
                } catch (Exception $e) {
                    $errors[] = 'Failed to update company';
                }
            }
            
            $this->data['errors'] = $errors;
            $this->data['post'] = $post;
        } else {
            $this->data['post'] = $company;
        }
        
        $tags = $this->model->TagModel->getAllOrdered();
        $companyTags = $this->model->CompanyTagModel->getTagsByCompany($id);
        $selectedTagIds = array_column($companyTags, 'id');
        
        $this->data['tags'] = $tags;
        $this->data['selectedTagIds'] = $selectedTagIds;
        $this->data['company'] = $company;
        $this->data['pageTitle'] = 'Edit Company';
        return $this->load->view('admin/company_form');
    }

    public function users()
    {
        $this->load->model('UserModel');
        $this->load->model('ReviewModel');
        
        $post = $this->getPostData();
        
        if (!empty($post)) {
            if (isset($post['action']) && $post['action'] == 'delete' && isset($post['id'])) {
                if ($post['id'] == $_SESSION['user_id']) {
                    $this->data['error'] = 'You cannot delete your own account';
                } else {
                    try {
                        $this->model->UserModel->delete($post['id']);
                        $this->data['success'] = 'User deleted successfully';
                    } catch (Exception $e) {
                        $this->data['error'] = 'Failed to delete user';
                    }
                }
            } elseif (isset($post['action']) && $post['action'] == 'update_role' && isset($post['id']) && isset($post['role'])) {
                if ($post['id'] == $_SESSION['user_id']) {
                    $this->data['error'] = 'You cannot change your own role';
                } else {
                    try {
                        $this->model->UserModel->update($post['id'], ['role' => $post['role']]);
                        $this->data['success'] = 'User role updated successfully';
                    } catch (Exception $e) {
                        $this->data['error'] = 'Failed to update user role';
                    }
                }
            }
        }
        
        $users = $this->model->UserModel->getAll([], 'created_at DESC');
        
        foreach ($users as &$user) {
            $reviews = $this->model->ReviewModel->getByUser($user['id']);
            $user['review_count'] = count($reviews);
        }
        
        $this->data['users'] = $users;
        $this->data['pageTitle'] = 'Manage Users';
        return $this->load->view('admin/users');
    }

    public function reviews()
    {
        $this->load->model('ReviewModel');
        $this->load->model('CompanyModel');
        $this->load->model('UserModel');
        
        $post = $this->getPostData();
        
        if (!empty($post)) {
            if (isset($post['action']) && $post['action'] == 'delete' && isset($post['id'])) {
                try {
                    $this->model->ReviewModel->delete($post['id']);
                    $this->data['success'] = 'Review deleted successfully';
                } catch (Exception $e) {
                    $this->data['error'] = 'Failed to delete review';
                }
            }
        }
        
        $reviews = $this->model->ReviewModel->getAll([], 'created_at DESC');
        
        foreach ($reviews as &$review) {
            $company = $this->model->CompanyModel->get($review['company_id']);
            $review['company'] = $company;
            
            if ($review['user_id']) {
                $user = $this->model->UserModel->get($review['user_id']);
                $review['user'] = $user;
            }
        }
        
        $this->data['reviews'] = $reviews;
        $this->data['pageTitle'] = 'Manage Reviews';
        return $this->load->view('admin/reviews');
    }

    public function tags()
    {
        $this->load->model('TagModel');
        $this->load->model('CompanyTagModel');
        
        $post = $this->getPostData();
        
        if (!empty($post)) {
            if (isset($post['action']) && $post['action'] == 'delete' && isset($post['id'])) {
                try {
                    $this->model->TagModel->delete($post['id']);
                    $this->data['success'] = 'Tag deleted successfully';
                } catch (Exception $e) {
                    $this->data['error'] = 'Failed to delete tag';
                }
            }
        }
        
        $tags = $this->model->TagModel->getAllOrdered();
        
        foreach ($tags as &$tag) {
            $companies = $this->model->CompanyTagModel->getCompaniesByTag($tag['id']);
            $tag['company_count'] = count($companies);
        }
        
        $this->data['tags'] = $tags;
        $this->data['pageTitle'] = 'Manage Tags';
        return $this->load->view('admin/tags');
    }

    public function tag($action = null, $id = null)
    {
        if ($action == 'create') {
            $this->tagCreate();
        } elseif ($action == 'edit' && $id) {
            $this->tagEdit($id);
        } else {
            header('Location: /admin/tags');
            exit;
        }
    }

    private function tagCreate()
    {
        $this->load->model('TagModel');
        
        $post = $this->getPostData();
        $errors = [];
        
        if (!empty($post)) {
            $name = trim($post['name'] ?? '');
            $color = trim($post['color'] ?? '#3b82f6');
            $description = trim($post['description'] ?? '');
            
            if (empty($name)) {
                $errors[] = 'Tag name is required';
            }
            
            if (empty($errors)) {
                $existing = $this->model->TagModel->findByName($name);
                if ($existing) {
                    $errors[] = 'A tag with this name already exists';
                }
            }
            
            if (empty($errors)) {
                try {
                    $tagData = [
                        'name' => $name,
                        'color' => $color,
                        'description' => $description
                    ];
                    
                    $this->model->TagModel->add($tagData);
                    header('Location: /admin/tags');
                    exit;
                } catch (Exception $e) {
                    $errors[] = 'Failed to create tag';
                }
            }
            
            $this->data['errors'] = $errors;
            $this->data['post'] = $post;
        }
        
        $this->data['pageTitle'] = 'Create Tag';
        return $this->load->view('admin/tag_form');
    }

    private function tagEdit($id)
    {
        $this->load->model('TagModel');
        
        $tag = $this->model->TagModel->get($id);
        
        if (!$tag) {
            header('Location: /admin/tags');
            exit;
        }
        
        $post = $this->getPostData();
        $errors = [];
        
        if (!empty($post)) {
            $name = trim($post['name'] ?? '');
            $color = trim($post['color'] ?? '#3b82f6');
            $description = trim($post['description'] ?? '');
            
            if (empty($name)) {
                $errors[] = 'Tag name is required';
            }
            
            if (empty($errors)) {
                $existing = $this->model->TagModel->findByName($name);
                if ($existing && $existing['id'] != $id) {
                    $errors[] = 'A tag with this name already exists';
                }
            }
            
            if (empty($errors)) {
                try {
                    $updateData = [
                        'name' => $name,
                        'color' => $color,
                        'description' => $description
                    ];
                    
                    $this->model->TagModel->update($id, $updateData);
                    header('Location: /admin/tags');
                    exit;
                } catch (Exception $e) {
                    $errors[] = 'Failed to update tag';
                }
            }
            
            $this->data['errors'] = $errors;
            $this->data['post'] = $post;
        } else {
            $this->data['post'] = $tag;
        }
        
        $this->data['tag'] = $tag;
        $this->data['pageTitle'] = 'Edit Tag';
        return $this->load->view('admin/tag_form');
    }

    public function feedback()
    {
        $this->load->model('FeedbackModel');
        
        $feedback = $this->model->FeedbackModel->getAll([], 'created_at DESC');
        
        $this->data['feedback'] = $feedback;
        $this->data['pageTitle'] = 'View Feedback';
        return $this->load->view('admin/feedback');
    }
}

