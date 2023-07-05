<?php

Enum Env: int
{
    case Development = 0;
    case Production  = 1;
}

Enum Method: string
{
    case GET    = 'get';
    case DELETE = 'delete';
    case PATCH  = 'patch';
    case POST   = 'post';
    case PUT    = 'put';
}
