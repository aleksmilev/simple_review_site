<?php

class Controller
{

    protected $data = [];
    protected $model = null;

    public function __construct()
    {
        $this->model = new stdClass();
    }

    protected function loadView($viewPath)
    {
        return new Layout($viewPath, $this->data);
    }

    protected function loadModel($model)
    {
        $modelPath = __DIR__ . '/../model/' . strtolower($model) . '.php';
        if (!file_exists($modelPath)) {
            throw new Exception('Model not found');
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


