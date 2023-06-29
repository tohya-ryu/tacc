<?php

class AuthController extends FrameworkControllerBase {

    public $auth;

    public function __construct()
    {
        $this->auth = new AuthService($this);
        $this->init_language();
    }

    public function login()
    {
        # GET | login form
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            $this->redirect($this->base_uri());
        } else {
            $view = new AuthView($this);
            $view->login();
            $this->response->send();
        }
    }

    public function login_submit()
    {
        # POST | validates login form data
        $this->response->set_type(FrameworkResponse::JSON);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            $this->response->set_data('redirect', $this->base_uri());
            $this->response->send();
        } else {
            $this->auth->form_login();
            if ($this->auth->user_exists()) {
                $this->response->set_data('redirect', $this->base_uri());
            }
            $this->response->send();
        }
    }

    public function logout()
    {
        # GET | logout and redirect to Index
        if ($this->auth->attempt_login())
            $this->auth->logout();
        $this->redirect($this->base_uri());
    }

    public function signup()
    {
        # GET | sign up form | sends confirmation mail on success
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            $this->redirect($this->base_uri());
        } else {
            $view = new AuthView($this);
            $view->signup();
        }
    }

    public function signup_submit()
    {
        # POST | validates form data
        $this->response->set_type(FrameworkResponse::JSON);
        $this->auth->use_csrf_prot();
        $this->auth->validate_signup();
        $response = $this->auth->handle_signup();
        echo json_encode($response);
    }

    public function signup_confirm()
    {
        # GET | Sign Up confirmation | Activates account linked to :token
    }

    public function pwreset_apply()
    {
        # GET / POST | E-Mail Form
    }

    public function pwreset_confirm()
    {
        # GET / POST | Password Form linked to :token
    }

}
