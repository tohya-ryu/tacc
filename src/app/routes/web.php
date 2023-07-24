<?php

require 'app/routes/auth.php';

$routes = [

    [ 'get', '/', 'main#home' ],
    [ 'get', 'a', 'main#home' ]

];

framework5\init\routing\register_routes($routes, $router);
