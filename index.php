<?php
    // We start by including the autoloader from Composer
    require 'vendor/autoload.php';

    // Then, we create a new instance of Slim
    $slim = new \Slim\App();

    // And a new instance of Templacat (handcrafted templating system)
    $templacat = new \Templacat\Templacat('template/tpl');

    // We load the template files into the templating system
    $templacat->load_template('header');
    $templacat->load_template('sidebar', 'PAGE_SIDEBAR'); // The sidebar will be loaded into the PAGE_SIDEBAR variable
    $templacat->load_template('footer');

    // [TEMP] Our first page
    $slim->get('/', function(Slim\Http\Request $request, Slim\Http\Response $response, array $args)
    {
        global $templacat;
        $response->write('<h1>Bonjour le monde</h1><p>Nothing to see here...</p>');   
        $templacat->set_variable('PAGE_TITLE', 'Homepage');

        return $response;
    })->setName('home');

    // Start buffering the output
    ob_start();
        // Render the view from Slim
        $slim->run();

        // And save the output to a Templacat variable
        $templacat->set_variable('PAGE_CONTENT', ob_get_contents());

    // Delete the buffer, without outputing the content
    ob_end_clean();
    
    // And finally render the template
    echo $templacat->render();
?>