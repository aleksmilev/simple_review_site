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

    public function validateFields($fields, $data)
    {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                ResponceApi::handle400();
            }
        }
    }
}