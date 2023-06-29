<?php

trait FrameworkLocaleEn {

    public function validation_error_required()
    {
        return "This field is required.";
    }

    public function validation_error_minlen($n)
    {
        return "Input requires at least $n symbols.";
    }

    public function validation_error_maxlen($n)
    {
        return "Input must not exceed $n symbols.";
    }

    public function validation_error_email()
    {
        return "E-Mail address appears to be invalid.";
    }

    public function validation_error_match_input($str)
    {
        return "Does not to match $str";
    }

    public function validation_csrf_check_failed()
    {
        return "Internal error. Please try again.";
    }

    public function validation_errors_notice()
    {
        return "Form validation failed. Please follow the respective ".
            "input hints below.";
    }

    public function auth_header_signup()
    {
        return "Sign up";
    }

    public function auth_header_login()
    {
        return "Login";
    }

    public function auth_inp_name_username()
    {
        return "Username";
    }

    public function auth_inp_name_email()
    {
        return "E-Mail";
    }

    public function auth_inp_name_password()
    {
        return "Password";
    }

    public function auth_inp_name_password_check()
    {
        return "Verify password";
    }

    public function auth_button_signup()
    {
        return "Register Account";
    }

    public function auth_button_login()
    {
        return "Login";
    }

    public function auth_remember_login()
    {
        return "Remember me";
    }

    public function auth_link_signup()
    {
        return "Sign up";
    }

    public function auth_link_pwreset()
    {
        return "I forgot my password";
    }

    public function auth_valid_login()
    {
        return "Login successful.";
    }

    public function auth_invalid_login()
    {
        return "Invalid login. Check your e-mail".
            " and password for correctness.";
    }

}
