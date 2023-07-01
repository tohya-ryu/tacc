<?php

class FrameworkControllerBase {

    protected $request;
    protected $lang;
    public    $response;

    public function set_request()
    {
        $this->request = FrameworkRequest::get();
    }

    public function init_response()
    {
        $this->response = FrameworkResponse::get();
    }

    public function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    public function base_uri($str = '')
    {
        if (!empty(AppConf::get('root_dir'))) {
            return "/".AppConf::get('root_dir')."/$str";
        } else {
            return "/$str";
        }
    }

    protected function init_language()
    {
        $request = FrameworkRequest::get();
        $request->init_accepted_languages();
        $this->lang = FrameworkLanguage::get();
        $this->lang->from_default(); 
        $this->lang->from_browser(); 
        $this->lang->from_cookie(); 
    }

}
