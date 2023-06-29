<?php

class MainView extends FrameworkViewBase {

    public $title;
    public $csrf_token;

    public $username;
    public $find_result;

    public function index()
    {
        $this->csrf_token = $this->controller->auth->get_csrf_token(true,
            'jlearn');
        $this->title = 'jlearn::practice';
        $this->username = $this->enc(
            $this->controller->auth->get_user_name());
        $this->set_layout('layout.html.php');
        $this->render('landing.html.php');
    }

    public function find()
    {
        $this->csrf_token = $this->controller->auth->get_csrf_token(true);
        $this->title = 'jlearn::search';
        $this->username = $this->enc(
            $this->controller->auth->get_user_name());
        $this->set_layout('layout.html.php');
        $this->find_result = $this->controller->service->find_result;
        $this->render('find.html.php');
    }

}
