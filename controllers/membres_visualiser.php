<?php
	// Check if the id is a number, and sanitize it
	if(!is_numeric($_GET['id']))
		die('Bad usage. $_GET[id] should be a number!');
	else
		$_GET['id'] = $slim->pdo->quote($_GET['id']);

	// Query the database
	$query = $slim->pdo->query('SELECT V_Membre.*, V_Identifiant.id_personne as compte_actif FROM V_Membre
								LEFT JOIN V_Identifiant ON V_Membre.id_personne = V_Identifiant.id_personne
								WHERE V_Membre.id_personne = ' . $_GET['id']);

	// Check if the id is valid
	if($query->rowCount() < 1)
		die('Nothing found');

	$line = $query->fetch();

	$templacat->set_variable("page_title", "Détails pour " . $line['prenom'] . ' ' . $line['nom']);
?>

<div class="panel panel-default contenu-page">
	<p><a href="membres_voir">« Retourner à la liste des membres</a></p>
    <h1>Fiche membre <a class="btn btn-warning pull-right" href="membres_editer?id=<?php echo $line['id_personne']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le membre</a></h1>

    <table class="table">
		<tr>
			<td class="titre-tableau">Nom</td>
			<td><span class="glyphicon glyphicon-user"></span> <?php echo $line['prenom'] . ' ' . $line['nom']; ?></td>
		</tr>
		<tr>
			<td class="titre-tableau">Statut</td>
			<td><span class="glyphicon glyphicon-user"></span>
				<?php 
					if($line['actif'] == 1)
						echo '<span class="label label-success">Membre actif</span>';
					else
						echo '<span class="label label-danger">Ancien membre</span>';
				?>
			</td>
		</tr>
		<tr>
			<td class="titre-tableau">Titre</td>
			<td><span class="glyphicon glyphicon-king"></span> TODO : FIX DU MCD
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
		<tr>
			<td class="titre-tableau">Accès à fortitudo</td>
			<td><span class="glyphicon glyphicon-eye-open"></span>
				<?php 
					if(!is_null($line['compte_actif']) && $line['actif'] == 1)
						echo ' <span class="label label-success">Activé</span>';
					elseif(!is_null($line['compte_actif']) && $line['actif'] == 0)
						echo ' <span class="label label-warning">Bloqué (ancien membre)</span>';
					else
						echo ' <span class="label label-danger">Désactivé</span>';
				?>
			</td>
		</tr>
	</table>
					
	<h3>Projets associés</h3>
	<table class="table">
		<?php
			// List all the projets for the corresponding member
			$query_projets = $slim->pdo->query('SELECT T_Projet.num_projet, titre_projet
				FROM TJ_Membre_Projet
				INNER JOIN T_Projet ON TJ_Membre_Projet.num_projet = T_Projet.num_projet
				WHERE id_membre = ' . $_GET['id'] . '
				ORDER BY num_projet DESC'
			) or die(var_dump($slim->pdo->errorInfo()));

			// If none can be found
			if($query_projets->rowCount() < 1)
				echo '<tr><td colspan="3" style="text-align:center">Aucun projet associé n\'a pu être trouvé</td></td>';
			
			// Otherwise
			else
			{
				echo '<tr><th>#</th><th>Titre du projet</th><th style="width: 50px">Action</th></tr>';

				while($projet = $query_projets->fetch())
				{
					echo '<tr>
						<td>' . $projet['num_projet'] . '</td>
						<td>' . $projet['titre_projet'] . '</td>
						<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser?id=' . $projet['num_projet'] . '"><span class="glyphicon glyphicon-search"></span></a></tr>
					</tr>';	
				}
			}
		?>
	</table>
</div>