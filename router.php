<?php
    // [TEMP] Our homepage
    $slim->get('/', function($request, $response, $args) use ($templacat, $slim, $config) { include 'controllers/homepage.php'; });

	// Project related pages	
		$slim->get('/projet_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_visualiser.php'; });
		$slim->get('/projet_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_voir.php'; });
		$slim->get('/projet_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_editer.php'; });
		$slim->get('/projet_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_ajouter.php'; });

		// Form action pages 
			$slim->post('/projet_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_ajouter_submit.php'; });
			$slim->post('/projet_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/projet_editer_submit.php'; });

	// Members related pages
		$slim->get('/membres_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_voir.php'; });
		$slim->get('/membres_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_visualiser.php'; });
		$slim->get('/membres_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_ajouter.php'; });
		$slim->get('/membres_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_editer.php'; });
		
		// Form action pages 
			$slim->post('/membres_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_ajouter_submit.php'; });
			$slim->post('/membres_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/membres_editer_submit.php'; });
	
	// Client related pages
		$slim->get('/clients_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_ajouter.php'; });
		$slim->get('/clients_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_editer.php'; });	
		$slim->get('/clients_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_voir.php'; });
		$slim->get('/clients_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_visualiser.php'; });
	
		// Form action pages 
			$slim->post('/clients_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_ajouter_submit.php'; });
			$slim->post('/clients_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/clients_editer_submit.php'; });
	
	// Salaries related pages
		$slim->get('/salaries_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_voir.php'; });
		$slim->get('/salaries_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_visualiser.php'; });
		$slim->get('/salaries_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_ajouter.php'; });
		$slim->get('/salaries_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_editer.php'; });
    
		// Form action pages 
			$slim->post('/salaries_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_ajouter_submit.php'; });
			$slim->post('/salaries_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/salaries_editer_submit.php'; });

	// Login related pages. These pages can be accessed without being logged-in
		$slim->get('/connexion', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion.php'; });
		$slim->get('/connexion_deconnecter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/deconnexion.php'; });
		$slim->get('/connexion_mdp_oublie', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion_mdp_oublie.php'; });
		$slim->get('/connexion_changer_mdp', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion_changer_mdp.php'; });
	
		// Form action pages 
			$slim->post('/connexion_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion_submit.php'; });
			$slim->post('/connexion_demande_nouveau_mdp', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion_demande_nouveau_mdp.php'; });
			$slim->post('/connexion_changer_mdp_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/connexion_changer_mdp_submit.php'; });

	// MOA related pages
		$slim->get('/moa_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_ajouter.php'; });
		$slim->get('/moa_ajouter_etape2', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_ajouter_etape2.php'; });
		$slim->get('/moa_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_visualiser.php'; });
		$slim->get('/moa_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_editer.php'; });

		// Form action pages 
			$slim->post('/moa_creer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_creer_submit.php'; });
			$slim->post('/moa_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_ajouter_submit.php'; });
			$slim->post('/moa_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/moa_editer_submit.php'; });

	// Account management related
		$slim->get('/mon_compte', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/mon_compte.php'; });

		// Form action pages 
		$slim->post('/changer_mdp_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/changer_mdp_submit.php'; });

	// Postes related
		$slim->get('/postes_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_voir.php'; });
		$slim->get('/postes_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_visualiser.php'; });
		$slim->get('/postes_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_ajouter.php'; });
		$slim->get('/postes_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_editer.php'; });
		
		// Form action pages 
			$slim->post('/postes_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_ajouter_submit.php'; });
			$slim->post('/postes_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/postes_editer_submit.php'; });

	// Services related
		$slim->get('/services_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/services_voir.php'; });
		$slim->get('/services_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/services_visualiser.php'; });
		$slim->get('/services_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/services_ajouter.php'; });
		$slim->get('/services_editer', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/services_editer.php'; });
		
		// Form action pages 
			$slim->post('/services_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/services_ajouter_submit.php'; });
			$slim->post('/services_editer_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/services_editer_submit.php'; });

	// Quotations related
		$slim->get('/devis_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_ajouter.php'; });
		$slim->get('/devis_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_ajouter_submit.php'; });
		$slim->get('/devis_visualiser', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_visualiser.php'; });
		$slim->get('/devis_valider', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_valider.php'; });
		$slim->get('/devis_fin_service', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_fin_service.php'; });
		$slim->get('/devis_supprimer_poste', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_supprimer_poste.php'; });
		$slim->get('/devis_supprimer_service', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_supprimer_service.php'; });
		
		// Form action pages
			$slim->post('/devis_ajouter_service_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_ajouter_service_submit.php'; });
			$slim->post('/devis_ajouter_poste_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/devis_ajouter_poste_submit.php'; });

	// Revenue related
		$slim->get('/paiements_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/paiements_voir.php'; });
		$slim->get('/paiements_ajouter', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/paiements_ajouter.php'; });

		// Form action pages
			$slim->post('/paiements_ajouter_submit', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/paiements_ajouter_submit.php'; });

	// Manage accounting
		$slim->get('/comptes_voir', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/comptes_voir.php'; });
		$slim->get('/comptes_generer_cdr', function($request, $response, $args) use ($templacat, $slim, $config){ include 'controllers/comptes_generer_cdr.php'; });
		
?>