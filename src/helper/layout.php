<?php

class Layout
{
    private $viewData = [];

    public function __construct($viewPath, $data = [])
    {
        $this->viewData = $data;

        $this->loadView('layout/header');
        $this->loadView($viewPath);
        $this->loadView('layout/footer');
    }
    
    private function loadView($viewPath)
    {
        extract($this->viewData);
        
        $contentPath = __DIR__ . '/../view/' . $viewPath . '.php';
        if (file_exists($contentPath)) {
            require $contentPath;
        }
    }
}