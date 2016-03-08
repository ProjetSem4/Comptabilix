<?php
    // [TEMP] Our homepage
    $slim->get('/', function($request, $response, $args) use ($templacat) { include 'controllers/homepage.php'; })->setName('home');

    // Installation wizard
    $slim->get('/install', function($request, $response, $args) use ($templacat) { include 'controllers/install.php'; })->setName('home');
?>