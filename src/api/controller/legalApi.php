<?php

class LegalApi extends ControllerApi
{
    public $requestRules = [
        'form' => ["POST"],
        'list' => ["GET"],
    ];

    public $adminMethods = [
        'list'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function form()
    {
        $data = $this->getPostData();

        $requiredFields = ['name', 'email', 'subject', 'message'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                ResponceApi::handle400();
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            ResponceApi::handle400();
        }

        $this->load->model('FeedbackModel');
        
        try {
            $this->model->FeedbackModel->add($data);
            ResponceApi::returnData(['message' => 'Feedback submitted successfully']);
        } catch (Exception $e) {
            ResponceApi::handle400();
        }
    }

    public function list()
    {
        $this->load->model('FeedbackModel');
        
        try {
            $feedback = $this->model->FeedbackModel->getAll([], 'created_at DESC');
            ResponceApi::returnData(['data' => $feedback]);
        } catch (Exception $e) {
            ResponceApi::handle400();
        }
    }
}

