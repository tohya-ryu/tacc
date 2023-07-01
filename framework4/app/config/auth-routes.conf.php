<?php

FrameworkRoute::set('get', 'auth/login', 'auth#login');
FrameworkRoute::set('post', 'auth/login', 'auth#login_submit');
FrameworkRoute::set('get', 'auth/logout', 'auth#logout');
FrameworkRoute::set('get', 'auth/signup', 'auth#signup');
FrameworkRoute::set('post', 'auth/signup', 'auth#signup_submit');
FrameworkRoute::set('get', 'auth/signup/confirm/:token',
    'auth#signup_confirm');
FrameworkRoute::set('get', 'auth/pwreset/apply', 'auth#pwreset_apply');
FrameworkRoute::set('post', 'auth/pwreset/apply', 'auth#pwreset_apply');
FrameworkRoute::set('get', 'auth/pwreset/confirm/:token',
    'auth#pwreset_confirm');
FrameworkRoute::set('post', 'auth/pwreset/confirm/:token',
    'auth#pwreset_confirm');

FrameworkRoute::set('get', 'test', 'main#test');
