<?php

class Controller
{

    protected $data = [];
    protected $model = null;

    public function __construct()
    {
        $this->model = new stdClass();
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            try {
                $this->loadModel('UserModel');
                $user = $this->model->UserModel->get($_SESSION['user_id']);
                if ($user) {
                    $this->data['user'] = $user;
                }
            } catch (Exception $e) {
            }
        }
    }

    protected function loadView($viewPath)
    {
        return new Layout($viewPath, $this->data);
    }

    protected function loadModel($model)
    {
        $modelFileName = preg_replace('/model$/i', '', $model);
        $modelPath = __DIR__ . '/../model/' . strtolower($modelFileName) . '.php';
        if (!file_exists($modelPath)) {
            throw new Exception('Model not found: ' . $modelPath);
        }

        require_once($modelPath);
        
        $addedModel = new $model();
        $this->model->$model = $addedModel;
    }

    protected function getPostData()
    {
        return $_POST;
    }
}


