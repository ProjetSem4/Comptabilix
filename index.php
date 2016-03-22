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

    // Then load the configuration file (if exists)
    if(is_file('config.php'))
        include 'config.php';
    else // Or redirect to the installation wizard
        header('Location : install/');

    // Set the name of the association
    $templacat->set_variable('ASSOCIATION_NAME', $config['association']);

    // Try to connect to the database
    try
    {
        $slim->pdo = new PDO
        (
            'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'],
            $config['db_user'],
            $config['db_pass'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
        );
    }
    catch (PDOException $e) { die('<h1>Unable to connect to the database</h1>'); }

    // Then include the router file
    include 'router.php';

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