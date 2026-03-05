<?php

class User extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['currentPage'] = 'user';
    }

    public function login()
    {
        $post = $this->getPostData();
        
        if (!empty($post)) {
            $username = $post['username'] ?? '';
            $password = $post['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $this->data['error'] = 'Please fill in all fields';
            } else {
                $this->load->model('UserModel');
                $user = $this->model->UserModel->findByUsername($username);
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    header('Location: /home');
                    exit;
                } else {
                    $this->data['error'] = 'Invalid username or password';
                }
            }
        }
        
        $this->data['pageTitle'] = 'Login';
        return $this->load->view('user/login');
    }

    public function register()
    {
        $post = $this->getPostData();
        
        if (!empty($post)) {
            $username = trim($post['username'] ?? '');
            $email = trim($post['email'] ?? '');
            $password = $post['password'] ?? '';
            $confirmPassword = $post['confirm_password'] ?? '';
            
            $errors = [];
            
            if (empty($username)) {
                $errors[] = 'Username is required';
            } elseif (strlen($username) < 3) {
                $errors[] = 'Username must be at least 3 characters';
            }
            
            if (empty($email)) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }
            
            if (empty($password)) {
                $errors[] = 'Password is required';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
            
            if ($password != $confirmPassword) {
                $errors[] = 'Passwords do not match';
            }
            
            if (empty($errors)) {
                $this->load->model('UserModel');
                
                if ($this->model->UserModel->findByUsername($username)) {
                    $errors[] = 'Username already exists';
                }
                
                if ($this->model->UserModel->findByEmail($email)) {
                    $errors[] = 'Email already exists';
                }
                
                if (empty($errors)) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    
                    try {
                        $this->model->UserModel->add([
                            'username' => $username,
                            'email' => $email,
                            'password_hash' => $passwordHash,
                            'role' => 'user'
                        ]);
                        
                        $this->data['success'] = true;
                        $this->data['message'] = 'Registration successful! You can now login.';
                    } catch (Exception $e) {
                        $errors[] = 'Registration failed. Please try again.';
                    }
                }
            }
            
            if (!empty($errors)) {
                $this->data['errors'] = $errors;
            }
        }
        
        $this->data['pageTitle'] = 'Register';
        return $this->load->view('user/register');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /home');
        exit;
    }

    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /user/login');
            exit;
        }
        
        $this->load->model('UserModel');
        $user = $this->model->UserModel->get($_SESSION['user_id']);
        
        if (!$user) {
            header('Location: /user/login');
            exit;
        }
        
        $post = $this->getPostData();
        if (!empty($post)) {
            $email = trim($post['email'] ?? '');
            $currentPassword = $post['current_password'] ?? '';
            $newPassword = $post['new_password'] ?? '';
            $confirmPassword = $post['confirm_password'] ?? '';
            
            $errors = [];
            
            if (!empty($email) && $email != $user['email']) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Invalid email format';
                } else {
                    $existingUser = $this->model->UserModel->findByEmail($email);
                    if ($existingUser && $existingUser['id'] != $user['id']) {
                        $errors[] = 'Email already in use';
                    }
                }
            }
            
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    $errors[] = 'Current password is required to change password';
                } elseif (!password_verify($currentPassword, $user['password_hash'])) {
                    $errors[] = 'Current password is incorrect';
                } elseif (strlen($newPassword) < 6) {
                    $errors[] = 'New password must be at least 6 characters';
                } elseif ($newPassword != $confirmPassword) {
                    $errors[] = 'New passwords do not match';
                }
            }
            
            if (empty($errors)) {
                $updateData = [];
                
                if (!empty($email) && $email != $user['email']) {
                    $updateData['email'] = $email;
                }
                
                if (!empty($newPassword)) {
                    $updateData['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                
                if (!empty($updateData)) {
                    try {
                        $this->model->UserModel->update($user['id'], $updateData);
                        $this->data['success'] = true;
                        $this->data['message'] = 'Profile updated successfully';
                        
                        $user = $this->model->UserModel->get($_SESSION['user_id']);
                    } catch (Exception $e) {
                        $errors[] = 'Update failed. Please try again.';
                    }
                }
            }
            
            if (!empty($errors)) {
                $this->data['errors'] = $errors;
            }
        }
        
        $this->load->model('ReviewModel');
        $userReviews = $this->model->ReviewModel->getByUser($user['id']);
        $reviewCount = count($userReviews);
        
        $this->data['user'] = $user;
        $this->data['reviewCount'] = $reviewCount;
        $this->data['pageTitle'] = 'My Profile';
        return $this->load->view('user/profile');
    }

    public function reviews()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /user/login');
            exit;
        }
        
        $this->load->model('ReviewModel');
        $reviews = $this->model->ReviewModel->getByUser($_SESSION['user_id']);
        
        $this->data['reviews'] = $reviews;
        $this->data['pageTitle'] = 'My Reviews';
        return $this->load->view('user/reviews');
    }

    public function log_as_user()
    {
        $this->load->model('UserModel');
        $user = $this->model->UserModel->findByUsername('john_doe');
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: /home');
            exit;
        }
        
        header('Location: /user/login');
        exit;
    }

    public function log_as_admin()
    {
        $this->load->model('UserModel');
        $user = $this->model->UserModel->findByUsername('admin');
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: /home');
            exit;
        }
        
        header('Location: /user/login');
        exit;
    }
}

