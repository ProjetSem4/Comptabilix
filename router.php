<?php
    // [TEMP] Our homepage
    $slim->get('/', function($request, $response, $args) use ($templacat) { include 'controllers/homepage.php'; })->setName('home');

    // Installation wizard
    $slim->get('/install', function($request, $response, $args) use ($templacat) { include 'controllers/install.php'; })->setName('home');
	
	$slim->get('/projet', function($request, $response, $args) use ($templacat){ include 'controllers/projet.php'; })->setName('home');
	
	$slim->get('/projet_visualiser', function($request, $response, $args) use ($templacat){ include 'controllers/projet_visualiser.php'; })->setName('home');
	
	$slim->get('/projet_voir', function($request, $response, $args) use ($templacat){ include 'controllers/projet_voir.php'; })->setName('home');
	
	$slim->get('/membres', function($request, $response, $args) use ($templacat){ include 'controllers/membres.php'; })->setName('home');
	
	$slim->get('/membres_voir', function($request, $response, $args) use ($templacat){ include 'controllers/membres_voir.php'; })->setName('home');
	
	$slim->get('/membres_visualiser', function($request, $response, $args) use ($templacat){ include 'controllers/membres_visualiser.php'; })->setName('home');
	
	$slim->get('/clients_ajouter', function($request, $response, $args) use ($templacat){ include 'controllers/clients_ajouter.php'; })->setName('home');
	
	$slim->get('/clients_voir', function($request, $response, $args) use ($templacat){ include 'controllers/clients_voir.php'; })->setName('home');
	
	$slim->get('/clients_visualiser', function($request, $response, $args) use ($templacat){ include 'controllers/clients_visualiser.php'; })->setName('home');
	
	$slim->get('/salaries_ajouter', function($request, $response, $args) use ($templacat){ include 'controllers/salaries_ajouter.php'; })->setName('home');
	
	$slim->get('/salaries_voir', function($request, $response, $args) use ($templacat){ include 'controllers/salaries_voir.php'; })->setName('home');
	
	$slim->get('/salaries_visualiser', function($request, $response, $args) use ($templacat){ include 'controllers/salaries_visualiser.php'; })->setName('home');
?>