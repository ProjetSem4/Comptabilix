<?php
	$templacat->set_variable("page_title", "Visualiser les clients");

	// Check if the id is a number, and sanitize it
	if(!is_numeric($_GET['id']))
		die('Bad usage. $_GET[id] should be a number!');
	else
		$_GET['id'] = $slim->pdo->quote($_GET['id']);

	// Query the database
	$query = $slim->pdo->query('SELECT * FROM V_Societe WHERE id_personne = ' . $_GET['id']);

	$line = $query->fetch();
?>

<div class="panel panel-default contenu-page">
	<p><a href="clients_voir">« Retourner à la liste des clients</a></p>
    <h1>Fiche client <a class="btn btn-warning pull-right" href="#"><span class="glyphicon glyphicon-pencil"></span> Éditer le client</a></h1>
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
	
	<h3>Maîtrises d'ouvrage <a class="btn btn-success pull-right" href="clients_ajouter.php"><span class="glyphicon glyphicon-plus"></span> Ajouter un MOA</a></h3>
	<table class="table">
		<tr>
			<th>#</th>
			<th>Nom du MOA</th>
			<th>Poste</th>
			<th style="width: 150px">Action</th>
		</tr>
		<?php
			for($i = 3; $i > 0; $i--)
echo '<tr>
	<td>' . $i . '</td>
	<td>Truc muche</td>
	<td>Commercial</td>
	<td><a class="btn btn-info" title="Visualiser le client" href="moa_visualiser.php"><span class="glyphicon glyphicon-user"></span></a>
	<a class="btn btn-warning" title="Éditer le client" href="moa_ajouter.php"><span class="glyphicon glyphicon-pencil"></span></a>
	<a class="btn btn-default disabled" title="Supprimer le client" href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
</tr>';
		?>
	</table>
	
	<h3>Projets</h3>
	<table class="table">
		<tr>
			<th>#</th>
			<th>Titre du projet</th>
			<th>Argent versé</th>
			<th style="width: 50px">Action</th>
		</tr>
		<tr>
			<td>4</td>
			<td>Site web entreprise SignaNet <span class="label label-success">En cours</span></td>
			<td>5 000,00€</td>
			<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser.php"><span class="glyphicon glyphicon-search"></span></a></tr>
		</tr>
		<?php
			for($i = 3; $i > 0; $i--)
echo '<tr>
	<td>' . $i . '</td>
	<td>Application de gestion</td>
	<td>' . mt_rand(250, 500) . ',00€</td>
	<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser.php"><span class="glyphicon glyphicon-search"></span></a></tr>
</tr>';
		?>
	</table>
	
	<h3>Services</h3>
	<table class="table">
		<tr>
			<th>#</th>
			<th>Service</th>
			<th>Projet lié</th>
			<th>Prix mensuel</th>
			<th style="width: 50px">Action</th>
		</tr>
		<tr>
			<td>1</td>
			<td>Hébergement</td>
			<td>Site web entreprise SignaNet <span class="label label-danger">Résilié</span></td>
			<td>25,00€</td>
			<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser.php"><span class="glyphicon glyphicon-search"></span></a></tr>
		</tr>
	</table>
</div>
            