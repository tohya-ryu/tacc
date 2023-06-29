<?php

class AuthView extends FrameworkViewBase {

    public $title;
    public $csrf_token;

    public function login()
    {
        $this->set_token();
        $this->title = 'Login';
        $this->set_layout('auth/layout.html.php');
        $this->render('auth/login.html.php');
    }

    public function signup()
    {
        $this->set_token();
        $this->title = 'Sign Up';
        $this->set_layout('auth/layout.html.php');
        $this->render('auth/signup.html.php');
    }

    private function set_token()
    {
        $this->csrf_token = $this->controller->auth->get_csrf_token(true);
    }

}
