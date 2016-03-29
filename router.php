<?php
    // [TEMP] Our homepage
    $slim->get('/', function($request, $response, $args) use ($templacat, $slim) { include 'controllers/homepage.php'; })->setName('home');

    // Installation wizard
    $slim->get('/install', function($request, $response, $args) use ($templacat, $slim) { include 'controllers/install.php'; })->setName('home');
	
	$slim->get('/projet', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet.php'; })->setName('home');
	
	$slim->get('/projet_visualiser', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_visualiser.php'; })->setName('home');
	
	$slim->get('/projet_voir', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_voir.php'; })->setName('home');
	
	$slim->get('/membres_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_ajouter.php'; })->setName('home');
	$slim->post('/membres_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_ajouter_submit.php'; })->setName('home');

	$slim->get('/membres_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_editer.php'; })->setName('home');
	$slim->post('/membres_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_editer_submit.php'; })->setName('home');

	$slim->get('/membres_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_voir.php'; })->setName('home');
	
	$slim->get('/membres_visualiser', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_visualiser.php'; })->setName('home');
	
	$slim->get('/clients_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_ajouter.php'; })->setName('home');
	$slim->post('/clients_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_ajouter_submit.php'; })->setName('home');

	$slim->get('/clients_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_editer.php'; })->setName('home');
	$slim->post('/clients_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_editer_submit.php'; })->setName('home');
	
	$slim->get('/clients_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_voir.php'; })->setName('home');
	
	$slim->get('/clients_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_visualiser.php'; })->setName('home');
	
	$slim->get('/salaries_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_ajouter.php'; })->setName('home');
	
	$slim->get('/salaries_voir', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_voir.php'; })->setName('home');
	
	$slim->get('/salaries_visualiser', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_visualiser.php'; })->setName('home');

	$slim->get('/connexion', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion.php'; })->setName('home');
	$slim->post('/connexion_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion_submit.php'; })->setName('home');

	$slim->get('/connexion_deconnecter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/deconnexion.php'; })->setName('home');
?>