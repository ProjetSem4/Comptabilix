<?php
    // We start by including the autoloader from Composer
    require 'vendor/autoload.php';

    // Start the PHP session system
    session_start();

    // Then, we create a new instance of Slim
    $slim = new \Slim\App();

    // And a new instance of Templacat (handcrafted templating system)
    $templacat = new \Templacat\Templacat('template/tpl');

    // We load the template files into the templating system
    $templacat->load_template('header');

    // If the requested URI isn't a connection page, then show the sidebar and the logged-in menu
    if(substr($_SERVER['REQUEST_URI'], 0, 10) !== '/connexion')
    {
        $templacat->load_template('sidebar', 'PAGE_SIDEBAR'); // The sidebar will be loaded into the PAGE_SIDEBAR variable
        $templacat->load_template('logged_in_menu', 'LOGGED_IN_MENU'); // Same for the logged-in menu

    }

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

    // Check if the user is connected, or if he tries to access a login page
    if((!isset($_SESSION['connection_state']['connected']) || $_SESSION['connection_state']['connected'] !== true) && substr($_SERVER['REQUEST_URI'], 0, 10) !== '/connexion')
        header('Location: /connexion'); // Then redirect to the login page
    
    // If the user has a name, then we set it
    if(isset($_SESSION['connection_state']['name']))
        $templacat->set_variable('USER_NAME', $_SESSION['connection_state']['name']);

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