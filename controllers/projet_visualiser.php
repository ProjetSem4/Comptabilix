<?php
	// Check if the id is a number, and sanitize it
	if(!is_numeric($_GET['id']))
		die('Bad usage. $_GET[id] should be a number!');
	else
		$_GET['id'] = $slim->pdo->quote($_GET['id']);

	// Query the database
	$query = $slim->pdo->query('SELECT ' . $config['db_prefix'] . 'T_Projet.*, ' . $config['db_prefix'] . 'V_MOA.id_personne AS id_moa, ' . $config['db_prefix'] . 'V_MOA.nom, ' . $config['db_prefix'] . 'V_MOA.prenom, ' . $config['db_prefix'] . 'V_Societe.raison_sociale, ' . $config['db_prefix'] . 'V_Societe.id_personne as id_societe
								FROM ' . $config['db_prefix'] . 'T_Projet
								INNER JOIN ' . $config['db_prefix'] . 'V_MOA ON ' . $config['db_prefix'] . 'T_Projet.id_MOA = ' . $config['db_prefix'] . 'V_MOA.id_personne
								INNER JOIN ' . $config['db_prefix'] . 'V_Societe ON ' . $config['db_prefix'] . 'T_Projet.id_Societe = ' . $config['db_prefix'] . 'V_Societe.id_personne
								WHERE num_projet = ' . $_GET['id']);

	// Check if the id is valid
	if($query->rowCount() < 1)
		die('Nothing found');

	$line = $query->fetch();

	$templacat->set_variable("page_title", "Détails pour " . $line['titre_projet']);

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
	<p><a href="projet_voir">« Retourner à la liste des projets</a></p>
    <h1>Fiche projet <a class="btn btn-warning pull-right" href="projet_editer?id=<?php echo $line['num_projet']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le projet</a></h1>
	
	<table class="table">
		<tr>
			<td class="titre-tableau">Titre du projet</td>
			<td><span class="glyphicon glyphicon-file"></span> <?php echo $line['titre_projet']; ?></td>
		</tr>
		<tr>
			<td class="titre-tableau">Date de création</td>
			<td><span class="glyphicon glyphicon-calendar"></span> <?php echo $line['date_creation']; ?></td>
		</tr>
		<tr>
			<td class="titre-tableau">Entreprise cliente</td>
			<td><span class="glyphicon glyphicon-briefcase"></span> <a href="clients_visualiser?id=<?php echo $line['id_societe']; ?>"><?php echo $line['raison_sociale']; ?></a></td>
		</tr>
		<tr>
			<td class="titre-tableau">Maitrise d'ouvrage</td>
			<td><span class="glyphicon glyphicon-user"></span> <a href="moa_voir?id=<?php echo $line['id_moa']; ?>"><?php echo $line['prenom'] . ' ' . $line['nom']; ?></a></td>
		</tr>
	</table>
	
	<h3>Maîtrises d'œuvre</h3>
	
	<table class="table">
		<?php
			// List all the MOE for the corresponding projet
			$query_moe = $slim->pdo->query('SELECT ' . $config['db_prefix'] . 'V_Membre.id_personne, ' . $config['db_prefix'] . 'V_Membre.nom, ' . $config['db_prefix'] . 'V_Membre.prenom
				FROM ' . $config['db_prefix'] . 'V_Membre
				INNER JOIN ' . $config['db_prefix'] . 'TJ_Membre_Projet ON ' . $config['db_prefix'] . 'V_Membre.id_personne = ' . $config['db_prefix'] . 'TJ_Membre_Projet.id_membre
				WHERE ' . $config['db_prefix'] . 'TJ_Membre_Projet.num_projet = ' . $_GET['id'] .'
				ORDER BY prenom, nom ASC');
	
			// If none can be found
			if($query_moe->rowCount() < 1)
				echo '<tr><td colspan="4" style="text-align:center">Aucune maitrîse d\'œuvre associé n\'a pu être trouvé</td></td>';
			
			// Otherwise
			else
			{
				echo '<tr><th>#</th><th>Nom du membre</th><th style="width: 50px">Action</th></tr>';

				while($moe = $query_moe->fetch())
				{
					echo '<tr>
						<td>' . $moe['id_personne'] . '</td>
						<td>' . $moe['prenom'] . ' ' . $moe['nom'] . '</td>
						<td><a class="btn btn-info" title="Visualiser le maître d\'œuvre" href="membres_visualiser?id=' . $moe['id_personne'] . '"><span class="glyphicon glyphicon-search"></span></a>
						</td>
					</tr>';
				}
			}
		?>
	</table>

	<h3>Devis <a class="btn btn-success pull-right" href="devis_ajouter?pid=<?php echo $line['num_projet']; ?>"><span class="glyphicon glyphicon-plus"></span> Créer un nouveau devis</a></h3>
	<table class="table">
		<?php
			// List all the quotations for the corresponding projet
			$query_quotations = $slim->pdo->query('SELECT *
				FROM ' . $config['db_prefix'] . 'T_Devis
				WHERE num_projet = ' . $_GET['id'] .'
				ORDER BY date_emission DESC');
	
			// If none can be found
			if($query_quotations->rowCount() < 1)
				echo '<tr><td colspan="4" style="text-align:center">Aucun devis associé n\'a pu être trouvé</td></td>';
			
			// Otherwise
			else
			{
				echo '<tr><th>#</th><th>Date d\'émission</th><th>Date d\'acceptation</th><th style="width: 50px">Action</th></tr>';

				while($quotation = $query_quotations->fetch())
				{
					// If there is no « date_acceptation »
					if(is_null($quotation['date_acceptation']))
						$quotation['date_acceptation'] = 'n/a'; // Then use a placeholder

					$bouton = null;
					if($quotation['est_accepte'] == 1)
						$bouton = ' <span class="label label-success">Accepté</span>';

					echo '<tr>
						<td>' . $quotation['num_devis'] . '</td>
						<td>' . $quotation['date_emission'] . '</td>
						<td>' . $quotation['date_acceptation'] . $bouton . '</td>
						<td><a class="btn btn-info" title="Visualiser le devis" href="devis_visualiser?id=' . $quotation['num_devis'] . '"><span class="glyphicon glyphicon-search"></span></a>
						</td>
					</tr>';
				}
			}
		?>
	</table>
</div>