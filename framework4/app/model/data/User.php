<?php

class User {

    private $db;

    private $login_state;
    private $auth_tokens;

    public $id;
    public $name;
    public $email;
    public $password;
    public $token;
    public $activated;
    public $tag;
    public $lang_tag;
    public $token_datetime;
    public $creation_datetime;

    public function __construct()
    {
        $this->login_state = false;
        $this->auth_tokens = null;
        $this->db = FrameworkStoreManager::get()->store();
    }

    public function check_login()
    {
        return $this->login_state;
    }

    public function login($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
        $this->login_state = true;
    }

    public function logout()
    {
        $this->login_state = false;
        $this->auth_tokens = null;

        $this->id                = null;
        $this->name              = null;
        $this->email             = null;
        $this->password          = null;
        $this->token             = null;
        $this->activated         = null;
        $this->tag               = null;
        $this->lang_tag          = null;
        $this->token_datetime    = null;
        $this->creation_datetime = null;
    }

    public function fetch_auth_tokens($force_refresh = false)
    {
        if (is_null($this->auth_tokens) || $force_refresh) {
            $sql = "SELECT * FROM `auth_token` WHERE `user_id` = ".
                (int)$this->id.";";
            $res = $this->db->query($sql);
            if ($res->num_rows > 0) {
                $this->auth_tokens = array();
                while ($row = $res->fetch_assoc()) {
                    array_push($this->auth_tokens, $row);
                }
            }
        }
    }

    public function get_auth_tokens()
    {
        return $this->auth_tokens;
    }
}
