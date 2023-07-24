<?php

require 'app/routes/web.php';
require 'app/routes/api.php';

$router->fallback = 'main#not_found';
