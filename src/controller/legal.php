<?php

class Legal extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['currentPage'] = 'legal';
    }

    public function privacy()
    {
        $this->data['pageTitle'] = 'Privacy Policy';
        return $this->load->view('legal/privacy');
    }

    public function terms()
    {
        $this->data['pageTitle'] = 'Terms of Service';
        return $this->load->view('legal/terms');
    }

    public function about()
    {
        $this->data['pageTitle'] = 'About Us';
        return $this->load->view('legal/about');
    }

    public function contact()
    {
        $post = $this->getPostData();
        if (!empty($post)) {
            $name = $post['name'] ?? '';
            $email = $post['email'] ?? '';
            $subject = $post['subject'] ?? '';
            $message = $post['message'] ?? '';

            $this->load->model('FeedbackModel');
            $this->model->FeedbackModel->add([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
            ]);

            $this->data['success'] = true;
            $this->data['message'] = 'Feedback submitted successfully';
        }

        $this->data['pageTitle'] = 'Contact';
        return $this->load->view('legal/contact');
    }
}