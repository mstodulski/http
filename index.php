<?php

use mstodulski\http\Request;

require_once "tests/bootstrap.php";

$routes = getRoutes();
$request = new Request($routes);

dump($request);
die;