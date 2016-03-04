<?php
    // We start by including the autoloader from Composer
    require 'vendor/autoload.php';

    // Then, we create a new instance of Slim
    $slim = new \Slim\App();

    // Test
    $slim->get('/', function(Slim\Http\Request $request, Slim\Http\Response $response, array $args) {
        $response->write('<h1>Bonjour le monde</h1><p>Nothing to see here...</p>');
        return $response;
    });

    // And finally we run the app
    $slim->run();
?>