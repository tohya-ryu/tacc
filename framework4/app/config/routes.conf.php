<?php

/*
FrameworkRoute::set('get', '/', 'main#index');
FrameworkRoute::set('get', 'update/counters', 'main#update_word_count');

FrameworkRoute::set('get', 'new/vocab', 'vocab#new_form');
FrameworkRoute::set('post', 'new/vocab', 'vocab#new_submit');
FrameworkRoute::set('post', 'practice/vocab', 'vocab#practice');
FrameworkRoute::set('get', 'edit/vocab/:id{[0-9]+}', 'vocab#edit_form');
FrameworkRoute::set('post', 'edit/vocab', 'vocab#edit_submit');
FrameworkRoute::set('get', 'fetch/vocab/:search', 'vocab#fetch');

FrameworkRoute::set('get', 'new/kanji', 'kanji#new_form');
FrameworkRoute::set('post', 'new/kanji', 'kanji#new_submit');
FrameworkRoute::set('post', 'practice/kanji', 'kanji#practice');
FrameworkRoute::set('get', 'edit/kanji/:id{[0-9]+}', 'kanji#edit_form');
FrameworkRoute::set('post', 'edit/kanji', 'kanji#edit_submit');
FrameworkRoute::set('get', 'fetch/kanji/:search', 'kanji#fetch');

FrameworkRoute::set('get', 'find', 'main#find_data');
FrameworkRoute::set('post', 'find', 'main#find_data_submit');

require 'app/config/auth-routes.conf.php';


#FrameworkRoute::set('get', 'page/data/:id{[0-9]+}', 'main#data');
#
 */
FrameworkRoute::set_default('main#index'); // 404 goes here
