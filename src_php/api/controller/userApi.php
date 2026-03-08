<?php

class UserApi extends ControllerApi
{
    public $requestRules = [
        'login' => ["POST"],
        'register' => ["POST"],
        'changePassword' => ["POST"],
        'changeEmail' => ["POST"],
        'user' => ["GET"],
        'reviews' => ["GET"],
        'getAllUsers' => ["GET"],
        'changeUserRole' => ["POST"],
        'deleteUser' => ["POST"],
    ];

    public $adminMethods = [
        'getAllUsers',
        'changeUserRole',
        'deleteUser',
    ];

    public function login()
    {
        $data = $this->getPostData();

        $requiredFields = ['username', 'password'];
        $this->validateFields($requiredFields, $data);

        $this->load->model('UserModel');
        $user = $this->model->UserModel->findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            ResponceApi::returnData(['message' => 'Invalid username or password'], 200);
        }

        $tokenData = [
            'id' => $user['id'],
            'name' => $user['username'],
            'role' => $user['role']
        ];

        $token = ValidationApi::encryptToken($tokenData);

        ResponceApi::returnData(['token' => $token]);
    }

    public function user()
    {
        $token = ValidationApi::getToken();
        if (empty($token)) {
            ResponceApi::handle401();
        }

        $tokenData = ValidationApi::decryptToken($token);
        if (!$tokenData || !isset($tokenData['id'])) {
            ResponceApi::handle401();
        }

        $this->load->model('UserModel');
        $user = $this->model->UserModel->get($tokenData['id']);

        if (!$user) {
            ResponceApi::handle401();
        }

        $this->load->model('ReviewModel');
        $userReviews = $this->model->ReviewModel->getByUser($user['id']);
        $reviewCount = count($userReviews);

        unset($user['password_hash']);
        ResponceApi::returnData([
            'user' => $user,
            'reviewCount' => $reviewCount
        ]);
    }

    public function changePassword()
    {
        $token = ValidationApi::getToken();
        if (empty($token)) {
            ResponceApi::handle401();
        }

        $tokenData = ValidationApi::decryptToken($token);
        if (!$tokenData || !isset($tokenData['id'])) {
            ResponceApi::handle401();
        }

        $data = $this->getPostData();

        $requiredFields = ['old_password', 'new_password'];
        $this->validateFields($requiredFields, $data);

        $this->load->model('UserModel');
        $user = $this->model->UserModel->get($tokenData['id']);

        if (!$user) {
            ResponceApi::handle401();
        }

        if (!password_verify($data['old_password'], $user['password_hash'])) {
            ResponceApi::returnData(['message' => 'Current password is incorrect'], 200);
        }

        if (strlen($data['new_password']) < 6) {
            ResponceApi::returnData(['message' => 'New password must be at least 6 characters'], 200);
        }

        $newPasswordHash = password_hash($data['new_password'], PASSWORD_DEFAULT);

        try {
            $this->model->UserModel->update($user['id'], ['password_hash' => $newPasswordHash]);
            ResponceApi::returnData(['message' => 'Password changed successfully']);
        } catch (Exception $e) {
            ResponceApi::handle400();
        }
    }

    public function changeEmail()
    {
        $token = ValidationApi::getToken();
        if (empty($token)) {
            ResponceApi::handle401();
        }

        $tokenData = ValidationApi::decryptToken($token);
        if (!$tokenData || !isset($tokenData['id'])) {
            ResponceApi::handle401();
        }

        $data = $this->getPostData();

        $requiredFields = ['email'];
        $this->validateFields($requiredFields, $data);

        $this->load->model('UserModel');
        $user = $this->model->UserModel->get($tokenData['id']);

        if (!$user) {
            ResponceApi::handle401();
        }

        if ($data['email'] == $user['email']) {
            ResponceApi::returnData(['message' => 'New email must be different from current email'], 200);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            ResponceApi::returnData(['message' => 'Invalid email format'], 200);
        }

        $existingUser = $this->model->UserModel->findByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != $user['id']) {
            ResponceApi::returnData(['message' => 'Email already in use'], 200);
        }

        try {
            $this->model->UserModel->update($user['id'], ['email' => $data['email']]);
            ResponceApi::returnData(['message' => 'Email changed successfully']);
        } catch (Exception $e) {
            ResponceApi::handle400();
        }
    }

    public function reviews()
    {
        $token = ValidationApi::getToken();
        if (empty($token)) {
            ResponceApi::handle401();
        }

        $tokenData = ValidationApi::decryptToken($token);
        if (!$tokenData || !isset($tokenData['id'])) {
            ResponceApi::handle401();
        }

        $this->load->model('ReviewModel');
        $reviews = $this->model->ReviewModel->getByUser($tokenData['id']);

        ResponceApi::returnData(['reviews' => $reviews]);
    }

    public function getAllUsers()
    {
        $this->load->model('UserModel');
        $this->load->model('ReviewModel');
        
        $users = $this->model->UserModel->getAll([], 'created_at DESC');
        
        foreach ($users as &$user) {
            $reviews = $this->model->ReviewModel->getByUser($user['id']);
            $user['review_count'] = count($reviews);
            unset($user['password_hash']);
        }
        
        ResponceApi::returnData(['users' => $users]);
    }

    public function changeUserRole()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id', 'role'];
        $this->validateFields($requiredFields, $data);
        
        $token = ValidationApi::getToken();
        $tokenData = ValidationApi::decryptToken($token);
        $currentUserId = $tokenData['id'] ?? null;
        
        if ($data['id'] == $currentUserId) {
            ResponceApi::returnData(['error' => 'You cannot change your own role'], 400);
        }
        
        if (!in_array($data['role'], ['user', 'admin'])) {
            ResponceApi::returnData(['error' => 'Invalid role. Must be "user" or "admin"'], 400);
        }
        
        $this->load->model('UserModel');
        $user = $this->model->UserModel->get($data['id']);
        
        if (!$user) {
            ResponceApi::returnData(['error' => 'User not found'], 404);
        }
        
        try {
            $this->model->UserModel->update($data['id'], ['role' => $data['role']]);
            ResponceApi::returnData(['message' => 'User role updated successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to update user role'], 400);
        }
    }

    public function deleteUser()
    {
        $data = $this->getPostData();
        
        $requiredFields = ['id'];
        $this->validateFields($requiredFields, $data);
        
        $token = ValidationApi::getToken();
        $tokenData = ValidationApi::decryptToken($token);
        $currentUserId = $tokenData['id'] ?? null;
        
        if ($data['id'] == $currentUserId) {
            ResponceApi::returnData(['error' => 'You cannot delete your own account'], 400);
        }
        
        $this->load->model('UserModel');
        $user = $this->model->UserModel->get($data['id']);
        
        if (!$user) {
            ResponceApi::returnData(['error' => 'User not found'], 404);
        }
        
        try {
            $this->model->UserModel->delete($data['id']);
            ResponceApi::returnData(['message' => 'User deleted successfully']);
        } catch (Exception $e) {
            ResponceApi::returnData(['error' => 'Failed to delete user'], 400);
        }
    }
}