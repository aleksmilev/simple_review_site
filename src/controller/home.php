<?php

class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
    
        $this->data['pageTitle'] = 'Home';
        $this->data['currentPage'] = 'home';
    }

    public function index()
    {
        $this->loadView('home');
    }

    public function test($param)
    {
        echo "Hello World test: $param";
    }
}