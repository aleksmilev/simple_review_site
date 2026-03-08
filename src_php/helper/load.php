<?php

class Load
{
    private $modelReference = null;
    private $dataReference = null;
    
    public function __construct($modelReference, &$dataReference)
    {
        $this->modelReference = $modelReference;
        $this->dataReference = &$dataReference;
    }

    public function model($model)
    {
        $modelFileName = preg_replace('/model$/i', '', $model);
        $modelPath = __DIR__ . '/../model/' . strtolower($modelFileName) . '.php';
        if (!file_exists($modelPath)) {
            throw new Exception('Model not found: ' . $modelPath);
        }

        require_once($modelPath);
        
        $addedModel = new $model();
        $this->modelReference->$model = $addedModel;
    }

    public function view($viewPath)
    {
        return new Layout($viewPath, $this->dataReference);
    }
}