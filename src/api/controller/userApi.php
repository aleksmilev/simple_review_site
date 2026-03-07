<?php

class UserApi extends ControllerApi
{
    public $requestRules = [
        'login' => ["POST"],
        'register' => ["POST"],
        'user' => ["GET"],
    ];

    public function login()
    {
        $data = $this->getPostData();

        $requiredFields = ['username', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                ResponceApi::handle400();
            }
        }

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

        unset($user['password_hash']);
        ResponceApi::returnData(['user' => $user]);
    }
}