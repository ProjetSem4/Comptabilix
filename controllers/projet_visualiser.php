<?php
	// Check if the id is a number, and sanitize it
	if(!is_numeric($_GET['id']))
		die('Bad usage. $_GET[id] should be a number!');
	else
		$_GET['id'] = $slim->pdo->quote($_GET['id']);

	// Query the database
	$query = $slim->pdo->query('SELECT T_Projet.*, V_MOA.id_personne AS id_moa, V_MOA.nom, V_MOA.prenom, V_Societe.raison_sociale, V_Societe.id_personne as id_societe
								FROM T_Projet
								INNER JOIN V_MOA ON T_Projet.id_MOA = V_MOA.id_personne
								INNER JOIN V_Societe ON T_Projet.id_Societe = V_Societe.id_personne
								WHERE num_projet = ' . $_GET['id']);

	// Check if the id is valid
	if($query->rowCount() < 1)
		die('Nothing found');

	$line = $query->fetch();

	$templacat->set_variable("page_title", "Détails pour " . $line['titre_projet']);
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
	
	<h3>Maîtrises d'œuvre <a class="btn btn-success pull-right" href="moe_ajouter?pid=<?php echo $line['num_projet']; ?>"><span class="glyphicon glyphicon-plus"></span> Ajouter une maîtrise d'œuvre</a></h3>
	
	<table class="table">
		<?php
			// List all the MOE for the corresponding projet
			$query_moe = $slim->pdo->query('SELECT V_Membre.id_personne, V_Membre.nom, V_Membre.prenom
				FROM V_Membre
				INNER JOIN TJ_Membre_Projet ON V_Membre.id_personne = TJ_Membre_Projet.id_membre
				WHERE TJ_Membre_Projet.num_projet = ' . $_GET['id'] .'
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
						<td>' . $moe['nom'] . ' ' . $moe['prenom'] . '</td>
						<td><a class="btn btn-info" title="Visualiser le maître d\'œuvre" href="moe_visualiser?id=' . $moe['id_personne'] . '"><span class="glyphicon glyphicon-search"></span></a>
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
				FROM T_Devis
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