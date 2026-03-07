<?php

class ControllerApi extends Controller
{
    public $adminMethods = [];

    public $requestRules = [];

    public function __construct()
    {
        parent::__construct();
    }
}