<?php

class Controller
{

    protected $data = [];
    protected $model = null;
    protected $load = null;

    public function __construct()
    {
        $this->model = new stdClass();
        $this->load = new Load($this->model, $this->data);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            try {
                $this->load->model('UserModel');
                $user = $this->model->UserModel->get($_SESSION['user_id']);
                if ($user) {
                    $this->data['user'] = $user;
                }
            } catch (Exception $e) {}
        }
    }

    protected function getPostData()
    {
        return $_POST;
    }

    protected function returnDataResponse($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}


