<?php

class LocaleEn implements LocaleInterface {
    use FrameworkLocaleEn;

    public function username_regex_failed()
    {
        return "Invalid username.";
    }

    public function signup_success($email)
    {
        return "Account creation successful. Follow the link sent to ".
            "<b>$email</b> to activate your account.";
    }

}
