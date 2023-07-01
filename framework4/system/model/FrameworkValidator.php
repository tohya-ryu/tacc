<?php

class FrameworkValidator {

    private $values;
    private $errors;
    private $err_cnt;
    private $print_errors_f;
    private $csrf_token_valid_f;

    public function __construct()
    {
        $this->values = array();
        $this->errors = array();
        $this->err_cnt = 0;
        $this->collect_errors_f = true;
        $this->csrf_token_valid_f = false;
    }

    public function dismiss_errors()
    {
        $this->collect_errors_f = false;
    }

    public function collect_errors()
    {
        $this->collect_errors_f = true;
    }

    public function is_valid()
    {
        return ($this->err_cnt < 1);
    }

    public function csrf_token_is_valid()
    {
        return $this->csrf_token_valid_f;
    }

    public function get_errors()
    {
        return $this->errors;
    }

    public function validate($value, ...$ar_values)
    {
        $this->values = array();
        array_push($this->values, $value);
        foreach ($ar_values as $v) {
            array_push($this->values, $v);
        }
    }

    public function validate_csrf_token($tok, $secure, $salt = false)
    {
        $ret = true;
        $code = null;
        if ($secure) {
            $code = $tok->to_hash($salt);
        } else {
            $code = $tok->code;
        }
        $request = FrameworkRequest::get();
        $this->validate($request->param->post('csrf-token'));
        if (!$this->equals($code,
            Framework::locale()->validation_csrf_check_failed()))
        {
            $this->csrf_token_valid_f = false;
            $ret = false;
        }
        return $ret;
    }

    # Rules
    
    public function required()
    {
        $ret = true;
        foreach ($this->values as $param) {
            if ($param->value == null || strlen($param->value) < 1) {
                $this->set_error($param->key,
                    Framework::locale()->validation_error_required());
                $ret = false;
            }
        }
        return $ret;
    }

    public function unique($table, $col, $msg = "")
    {
        $db = FrameworkStoreManager::get()->store();
        $ret = true;
        foreach ($this->values as $param) {
            $sql = "SELECT COUNT(`$col`) FROM `$table` WHERE `$col` = ?";
            $email = $param->value;
            $res = $db->pquery($sql, "s", $email)->fetch_row()[0];
            if ($res === 1) {
                $this->set_error($param->key, $msg);
                $ret = false;
            }
        }
        return $ret;
    }

    public function minlen($n)
    {
        $ret = true;
        foreach ($this->values as $param) {
            if (!is_null($param->value)) {
                if (strlen($param->value) < $n) {
                    $this->set_error($param->key,
                        Framework::locale()->validation_error_minlen($n));
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    public function maxlen($n)
    {
        $ret = true;
        foreach ($this->values as $param) {
            if (!is_null($param->value)) {
                if (strlen($param->value) > $n) {
                    $this->set_error($param->key,
                        Framework::locale()->validation_error_maxlen($n));
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    public function regex_match($ptn, $msg)
    {
        $ret = true;
        foreach ($this->values as $param) {
            if (!is_null($param->value)) {
                if (!preg_match($ptn, $param->value)) {
                    $this->set_error($param->key, $msg);
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    public function email()
    {
        $ret = true;
        foreach ($this->values as $param) {
            if (!is_null($param->value)) {
                if (!filter_var($param->value, FILTER_VALIDATE_EMAIL)) {
                    $this->set_error($param->key,
                        Framework::locale()->validation_error_email());
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    public function match_input($str, $input_name)
    {
        $ret = true;
        foreach ($this->values as $param) {
            if (!is_null($param->value)) {
                if (!($str === $param->value)) {
                    $this->set_error($param->key,
                        Framework::locale()->validation_error_match_input(
                            $input_name));
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    public function equals($str, $error_msg)
    {
        $ret = true;
        foreach ($this->values as $param) {
            if (!is_null($param->value)) {
                if (!($str === $param->value)) {
                    $this->set_error($param->key, $error_msg);
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    public function set_error($key, $msg)
    {
        $this->err_cnt++;
        if ($this->collect_errors_f) {
            if (!array_key_exists($key, $this->errors)) {
                $this->errors[$key] = array();
            }
            array_push($this->errors[$key], $msg);
        }
    }

}
