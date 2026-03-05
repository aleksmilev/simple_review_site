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
        $this->loadView('legal/privacy');
    }

    public function terms()
    {
        $this->data['pageTitle'] = 'Terms of Service';
        $this->loadView('legal/terms');
    }

    public function about()
    {
        $this->data['pageTitle'] = 'About Us';
        $this->loadView('legal/about');
    }

    public function contact()
    {
        $post = $this->getPostData();
        if (!empty($post)) {
            $name = $post['name'] ?? '';
            $email = $post['email'] ?? '';
            $subject = $post['subject'] ?? '';
            $message = $post['message'] ?? '';

            $this->loadModel('FeedbackModel');
            $this->model->FeedbackModel->add([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
            ]);

            $this->data['success'] = true;
            $this->data['message'] = 'Feedback submitted successfully';
        } else {
            $this->data['error'] = true;
            $this->data['message'] = 'Feedback submission failed';
        }

        $this->data['pageTitle'] = 'Contact';
        $this->loadView('legal/contact');
    }
}