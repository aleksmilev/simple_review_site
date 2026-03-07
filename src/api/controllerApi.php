<?php

class ControllerApi extends Controller
{
    public $adminMethods = [];

    public $requestRules = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function getPostData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}