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


        ResponceApi::returnData(['metgod' => 'Add']);
    }

    public function list()
    {
        ResponceApi::returnData(['method' => 'Get']);
    }
}

