<?php

interface LocaleInterface {

    # app
    public function username_regex_failed();
    public function signup_success($email);

    # framework
    public function validation_error_required();
    public function validation_error_minlen($n);
    public function validation_error_maxlen($n);
    public function validation_error_email();
    public function validation_error_match_input($str);
    public function validation_csrf_check_failed();
    public function validation_errors_notice();

    # authentication
    public function auth_header_signup();
    public function auth_header_login();
    public function auth_inp_name_username();
    public function auth_inp_name_email();
    public function auth_inp_name_password();
    public function auth_inp_name_password_check();
    public function auth_button_signup();
    public function auth_button_login();
    public function auth_remember_login();

    public function auth_valid_login();
    public function auth_invalid_login();

    public function auth_link_signup();
    public function auth_link_pwreset();

}
