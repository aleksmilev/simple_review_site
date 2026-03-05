<?php

class Layout
{
    public function __construct($viewPath, $data = [])
    {
        $this->applyData($data);

        $this->loadHeader();
        $this->loadView($viewPath);
        $this->loadFooter();
    }

    private function loadHeader()
    {
        $this->loadView('layout/header');
    }

    private function loadFooter()
    {
        $this->loadView('layout/footer');
    }

    private function loadView($viewPath)
    {
        $contentPath = __DIR__ . '/../view/' . $viewPath . '.php';
        if (file_exists($contentPath)) {
            require $contentPath;
        }
    }

    private function applyData($data = [])
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
    }

    private function loadLayout($viewPath, $data = [])
    {
        $this->loadHeader();
        $this->loadContent($viewPath, $data);
        $this->loadFooter();
    }
}