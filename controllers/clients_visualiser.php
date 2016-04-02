<div class="col-lg-9">
		<?php
			// Check if the id is a number, and sanitize it
			if(!is_numeric($_GET['id']))
				die('Bad usage. $_GET[id] should be a number!');
			else
				$_GET['id'] = $slim->pdo->quote($_GET['id']);
		
			// Query the database
			$query = $slim->pdo->query('SELECT * FROM ' . $config['db_prefix'] . 'V_Societe WHERE id_personne = ' . $_GET['id']);
		
			// Check if the id is valid
			if($query->rowCount() < 1)
				die('Nothing found');
		
			$line = $query->fetch();
		
			$templacat->set_variable("page_title", "Détails pour " . $line['raison_sociale']);
		
			// Show message(s), if needed
		    if(isset($_SESSION['fortitudo_messages']) && is_array($_SESSION['fortitudo_messages']))
		    {
		        // For each message
		        foreach($_SESSION['fortitudo_messages'] as $message)
		        {
		            // Use a different layout, determined by the type of the message
		            switch($message['type'])
		            {
		                case 'error' : 
		                    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message['content'] . '</div>';
		                    break;
		            
		                case 'success' : 
		                    echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message['content'] . '</div>';
		                    break;
		            }
		        }
		
		        // Clean the message queue
		        $_SESSION['fortitudo_messages'] = array();
		    }
		?>
		
		<div class="panel panel-default contenu-page">
			<p><a href="clients_voir">« Retourner à la liste des clients</a></p>
		    <h1>Fiche client <a class="btn btn-warning pull-right" href="clients_editer?id=<?php echo $line['id_personne']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le client</a></h1>
			<table class="table">
				<tr>
					<td class="titre-tableau">Raison sociale</td>
					<td><span class="glyphicon glyphicon-briefcase"></span> <?php echo $line['raison_sociale']; ?></td>
				</tr>
				<tr>
					<td class="titre-tableau">Adresse</td>
					<td><span class="glyphicon glyphicon-home"></span> <?php echo $line['adresse']; ?></td>
				</tr>
				<tr>
					<td class="titre-tableau">Ville</td>
					<td><span class="glyphicon glyphicon-map-marker"></span> <?php echo $line['code_postal'] . ' ' . $line['ville']; ?></td>
				</tr>
				<tr>
					<td class="titre-tableau">Numéro de téléphone</td>
					<td><span class="glyphicon glyphicon-earphone"></span> <?php echo $line['telephone']; ?></td>
				</tr>
				<tr>
					<td class="titre-tableau">Adresse e-mail</td>
					<td><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:<?php echo $line['mail']; ?>"><?php echo $line['mail']; ?></a></td>
				</tr>
			</table>
			
			<h3>Maîtrises d'ouvrage <a class="btn btn-success pull-right" href="moa_ajouter?cid=<?php echo $line['id_personne']; ?>"><span class="glyphicon glyphicon-plus"></span> Ajouter un MOA</a></h3>
			<table class="table">
				<?php
					// List all the MOAs for the corresponding client
					$query_moa = $slim->pdo->query('
						SELECT id_personne, titre, nom, prenom
						FROM ' . $config['db_prefix'] . 'TJ_Societe_MOA
						INNER JOIN ' . $config['db_prefix'] . 'V_MOA ON id_MOA = id_personne
						WHERE id_societe = ' . $_GET['id'] . '
						ORDER BY id_personne DESC'
					);
		
					// If none can be found
					if($query_moa->rowCount() < 1)
						echo '<tr><td colspan="4" style="text-align:center">Aucun MOA associé n\'a pu être trouvé</td></td>';
					
					// Otherwise
					else
					{
						echo '<tr><th>#</th><th>Nom du MOA</th><th>Poste</th><th style="width: 100px">Action</th></tr>';
		
						while($moa = $query_moa->fetch())
						{
							echo '<tr>
								<td>' . $moa['id_personne'] . '</td>
								<td>' . $moa['prenom'] . ' ' . $moa['nom'] . '</td>
								<td>' . $moa['titre'] . '</td>
								<td><a class="btn btn-info" title="Visualiser le MOA" href="moa_visualiser?id=' . $moa['id_personne'] . '"><span class="glyphicon glyphicon-user"></span></a>
								<a class="btn btn-warning" title="Éditer le MOA" href="moa_editer?id=' . $moa['id_personne'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
							</tr>';
						}
					}
				?>
			</table>
			
			<h3>Projets</h3>
			<table class="table">
				<?php
					// List all the projets created for the corresponding client
					$query_projects = $slim->pdo->query('
						SELECT num_projet, titre_projet
						FROM ' . $config['db_prefix'] . 'T_Projet
						WHERE id_societe = ' . $_GET['id'] . '
						ORDER BY date_creation DESC
					');
		
					// If none is found
					if($query_projects->rowCount() < 1)
						echo '<tr><td colspan="5" style="text-align:center">Aucun projet associé n\'a pu être trouvé</td></td>';
					
					// Otherwise
					else
					{
						echo '<tr><th>#</th><th>Titre du projet</th><th>Argent versé</th><th>Argent dû</th><th style="width: 50px">Action</th></tr>';
		
						while($projet = $query_projects->fetch())
						{
							// See how much money has been given by the client
							$query_given_money = $slim->pdo->query('
								SELECT SUM(quantite_payee) as prix
								FROM ' . $config['db_prefix'] . 'T_Devis
								INNER JOIN ' . $config['db_prefix'] . 'T_Paiement
								ON ' . $config['db_prefix'] . 'T_Devis.num_devis = ' . $config['db_prefix'] . 'T_Paiement.num_devis
								WHERE num_projet=' . $projet['num_projet'] . ' AND est_accepte=1'
							);
		
							$given_money = $query_given_money->fetch();
		
							// See how much money is due by the client
							$query_due_money = $slim->pdo->query('
								SELECT SUM(tarif_horaire * nbr_heures) as prix_total
								FROM ' . $config['db_prefix'] . 'T_Devis
								INNER JOIN ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste
								ON ' . $config['db_prefix'] . 'T_Devis.num_devis = ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.num_devis
								INNER JOIN ' . $config['db_prefix'] . 'T_Poste
								ON ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.num_poste = ' . $config['db_prefix'] . 'T_Poste.num_poste
								WHERE num_projet=' . $projet['num_projet'] . ' AND est_accepte=1'
							);
		
							$due_money = $query_due_money->fetch();
		
							// Add a message if money is due
							$suffix = null;
		
							// ... by the client
							if($given_money['prix'] < $due_money['prix_total'])
								$suffix = ' <span class="label label-success">En cours</span>';
		
							// ... or by the association (which isn't a normal behaviour)
							else if($given_money['prix'] > $due_money['prix_total'])
								$suffix = ' <span class="label label-error">Erreur de trésorerie</span>';
		
		
							// Money given
							$price = $given_money['prix'] != '' ? $given_money['prix'] : '0.00';
							$price = str_replace('.', ',', $price);
		
							// Money due
							$total_price = $due_money['prix_total'] != '' ? $due_money['prix_total'] : '0.00';
							$total_price = str_replace('.', ',', $total_price);
		
							echo '<tr>
								<td>' . $projet['num_projet'] . '</td>
								<td>' . $projet['titre_projet'] . $suffix . '</td>
								<td>' . $price . ' ' . $config['currency'] . '</td>
								<td>' . $total_price . ' ' . $config['currency'] . '</td>
								<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser?id=' . $projet['num_projet'] . '"><span class="glyphicon glyphicon-search"></span></a></tr>
							</tr>';
						}
					}
				?>
			</table>
			
			<h3>Services</h3>
			<table class="table">
				<?php
					// List all the projets created for the corresponding client
					$query_services = $slim->pdo->query('
						SELECT titre_projet, ' . $config['db_prefix'] . 'T_Service.num_service, date_fin, libelle, tarif_mensuel
						FROM ' . $config['db_prefix'] . 'T_Devis
						INNER JOIN ' . $config['db_prefix'] . 'TJ_Devis_Service
						ON ' . $config['db_prefix'] . 'T_Devis.num_devis = ' . $config['db_prefix'] . 'TJ_Devis_Service.num_devis
						INNER JOIN ' . $config['db_prefix'] . 'T_Service
						ON ' . $config['db_prefix'] . 'TJ_Devis_Service.num_service = ' . $config['db_prefix'] . 'T_Service.num_service
						INNER JOIN ' . $config['db_prefix'] . 'T_Projet
						On ' . $config['db_prefix'] . 'T_Devis.num_projet = ' . $config['db_prefix'] . 'T_Projet.num_projet
						WHERE ' . $config['db_prefix'] . 'T_Devis.num_projet=' . $_GET['id'] . ' AND est_accepte=1
						ORDER BY ' . $config['db_prefix'] . 'TJ_Devis_Service.date_debut DESC
					');
		
					// If none is found
					if($query_services->rowCount() < 1)
						echo '<tr><td colspan="5" style="text-align:center">Aucun service associé n\'a pu être trouvé</td></td>';
					
					// Otherwise
					else
					{
						echo '<tr><th>#</th><th>Service</th><th>Projet lié</th><th>Prix mensuel</th><th style="width: 50px">Action</th></tr>';
		
						while($service = $query_services->fetch())
						{
							$price = str_replace('.', ',', $service['tarif_mensuel']);
		
							// Add a suffix if the service is no longer provided
							$suffix = null;
							if(!is_null($service['date_fin']))
								$suffix = ' <span class="label label-danger">Résilié</span>';
		
							echo '<tr>
								<td>' . $service['num_service'] . '</td>
								<td>' . $service['libelle'] . '</td>
								<td>' . $service['titre_projet'] . $suffix . '</td>
								<td>' . $price . ' ' . $config['currency'] . '</td>
								<td><a class="btn btn-info" title="Visualiser le projet" href="service_visualiser?id=' . $service['num_service'] . '"><span class="glyphicon glyphicon-search"></span></a></tr>
							</tr>';
						}
					}
				?>
			</table>
		</div></div>
