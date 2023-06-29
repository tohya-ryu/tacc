<?php

class FrameworkResponse {
    use FrameworkSingleton;
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'type'
    );

    const HTML = 1;
    const JSON = 2;

    const VALID_KEEP = 'valid-keep';
    const VALID_CLEAR = 'valid-clear';
    const INVALID = 'invalid';

    private $type;

    # json response data
    private $data = array();

    public function set_type($type)
    {
        if (in_array($type, array(FrameworkResponse::JSON,
            FrameworkResponse::HTML)))
        {
            $this->type = $type;
        } else {
            debug_print_backtrace(0,1);
            trigger_error("Invalid response type '$type'.", E_USER_ERROR);
        }
    }

    public function set_data($key, $data)
    {
        $this->data[$key] = $data;
    }

    public function send()
    {
        if (!isset($this->data['debug']))
            $this->data['debug'] = false;
        if (!isset($this->data['redirect']))
            $this->data['redirect'] = false;
        switch ($this->type) {
        case FrameworkResponse::HTML:
            $this->html_response();
            break;
        case FrameworkResponse::JSON;
            $this->json_response(); 
            break;
        default:
            debug_print_backtrace(0,1);
            trigger_error("Response type undefined.", E_USER_ERROR);
        }
    }

    private function html_response()
    {
    }

    private function json_response()
    {
        echo json_encode($this->data);
    }

}
