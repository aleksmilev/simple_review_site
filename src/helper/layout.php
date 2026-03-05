<?php

class Layout
{
    private $viewData = [];

    public function __construct($viewPath, $data = [])
    {
        $this->applyData($data);

        $this->loadHeader();
        $this->loadView($viewPath);
        $this->loadFooter();
    }

    private function loadHeader()
    {
        extract($this->viewData);
        $this->loadView('layout/header');
    }

    private function loadFooter()
    {
        extract($this->viewData);
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

    private function applyData($data = [])
    {
        $this->viewData = $data;
    }

    private function loadLayout($viewPath, $data = [])
    {
        $this->loadHeader();
        $this->loadContent($viewPath, $data);
        $this->loadFooter();
    }
}