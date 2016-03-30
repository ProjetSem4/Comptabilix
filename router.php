<?php
    // [TEMP] Our homepage
    $slim->get('/', function($request, $response, $args) use ($templacat, $slim) { include 'controllers/homepage.php'; })->setName('home');

    // Installation wizard
    $slim->get('/install', function($request, $response, $args) use ($templacat, $slim) { include 'controllers/install.php'; })->setName('home');
	
	$slim->get('/projet_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_ajouter.php'; })->setName('home');
	$slim->post('/projet_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_ajouter_submit.php'; })->setName('home');

	$slim->get('/projet_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_editer.php'; })->setName('home');
	$slim->post('/projet_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_editer_submit.php'; })->setName('home');

	$slim->get('/projet_visualiser', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/projet_visualiser.php'; })->setName('home');
	
	$slim->get('/projet_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_voir.php'; })->setName('home');
	
	$slim->get('/membres_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_ajouter.php'; })->setName('home');
	$slim->post('/membres_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_ajouter_submit.php'; })->setName('home');

	$slim->get('/membres_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_editer.php'; })->setName('home');
	$slim->post('/membres_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_editer_submit.php'; })->setName('home');

	$slim->get('/membres_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_voir.php'; })->setName('home');
	
	$slim->get('/membres_visualiser', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/membres_visualiser.php'; })->setName('home');
	
	$slim->get('/clients_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_ajouter.php'; })->setName('home');
	$slim->post('/clients_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_ajouter_submit.php'; })->setName('home');

	$slim->get('/clients_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_editer.php'; })->setName('home');
	$slim->post('/clients_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/clients_editer_submit.php'; })->setName('home');
	
	$slim->get('/clients_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_voir.php'; })->setName('home');
	
	$slim->get('/clients_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_visualiser.php'; })->setName('home');
	
	$slim->get('/salaries_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_ajouter.php'; })->setName('home');
	$slim->post('/salaries_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_ajouter_submit.php'; })->setName('home');

	$slim->get('/salaries_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_editer.php'; })->setName('home');
	$slim->post('/salaries_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/salaries_editer_submit.php'; })->setName('home');
    
	$slim->get('/salaries_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_voir.php'; })->setName('home');
	
	$slim->get('/salaries_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_visualiser.php'; })->setName('home');

	$slim->get('/connexion', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion.php'; })->setName('home');
	$slim->post('/connexion_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion_submit.php'; })->setName('home');

	$slim->get('/connexion_deconnecter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/deconnexion.php'; })->setName('home');

	$slim->get('/moa_ajouter', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_ajouter.php'; })->setName('home');
	$slim->post('/moa_creer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_creer_submit.php'; })->setName('home');

	$slim->get('/moa_ajouter_etape2', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_ajouter_etape2.php'; })->setName('home');
	$slim->post('/moa_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_ajouter_submit.php'; })->setName('home');

	$slim->get('/moa_visualiser', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_visualiser.php'; })->setName('home');

	$slim->get('/moa_editer', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_editer.php'; })->setName('home');
	$slim->post('/moa_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/moa_editer_submit.php'; })->setName('home');

	$slim->get('/mon_compte', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/mon_compte.php'; })->setName('home');
	$slim->post('/changer_mdp_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/changer_mdp_submit.php'; })->setName('home');

	$slim->get('/connexion_mdp_oublie', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion_mdp_oublie.php'; })->setName('home');
	$slim->post('/connexion_demande_nouveau_mdp', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion_demande_nouveau_mdp.php'; })->setName('home');

	$slim->get('/connexion_changer_mdp', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion_changer_mdp.php'; })->setName('home');
	$slim->post('/connexion_changer_mdp_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/connexion_changer_mdp_submit.php'; })->setName('home');

	$slim->get('/postes_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_voir.php'; })->setName('home');
	$slim->get('/postes_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_visualiser.php'; })->setName('home');
	
	$slim->get('/postes_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_ajouter.php'; })->setName('home');
	$slim->post('/postes_ajouter_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/postes_ajouter_submit.php'; })->setName('home');

	$slim->get('/postes_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_editer.php'; })->setName('home');
	$slim->post('/postes_editer_submit', function($request, $response, $args) use ($templacat, $slim){ include 'controllers/postes_editer_submit.php'; })->setName('home');
?>