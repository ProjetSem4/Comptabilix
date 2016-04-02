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

	$templacat->set_variable("page_title", "Éditer " . $line['raison_sociale']);

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
	<p><a href="clients_voir?id=<?php echo $line['id_personne']; ?>">« Retourner sur la fiche client</a></p>
    <h1>Éditer la fiche de <?php echo $line['raison_sociale']; ?></h1>
                    
	<form class="" role="form" method="post" action="clients_editer_submit">
		<h3>Informations sur l'entreprise</h3>
		
		<div class="form-group col-sm-12">
			<label for="rs">Raison sociale du client :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
				<input id="rs" name="rs" type="text" class="form-control" placeholder="Raison sociale" value="<?php echo $line['raison_sociale']; ?>" required>
			</div>
		</div>
		
		<div class="form-group col-sm-12">
			<label for="adresse">Adresse du client :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
				<input id="adresse" name="adresse" type="text" class="form-control" placeholder="Adresse" value="<?php echo $line['adresse']; ?>" required>
			</div>
		</div>
		<div class="form-group col-sm-4">
			<label for="cp">Code postal :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
				<input id="cp" name="cp" type="text" class="form-control" placeholder="Code postal" value="<?php echo $line['code_postal']; ?>" required>
			</div>
		</div>
		<div class="form-group col-sm-8">
			<label for="email">Ville :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
				<input type="text" name="ville" class="form-control" placeholder="Ville" value="<?php echo $line['ville']; ?>" required>
			</div>
		</div>
		
		<div class="form-group col-sm-6">
			<label for="email">Numéro de téléphone :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
				<input type="text" name="tel" class="form-control" placeholder="Téléphone" value="<?php echo $line['telephone']; ?>" required>
			</div>
		</div>
		<div class="form-group col-sm-6">
			<label for="email">Adresse e-mail :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
				<input type="email" name="email" class="form-control" placeholder="E-mail" value="<?php echo $line['mail']; ?>" required>
			</div>
		</div>

		<input type="hidden" name="client_id" value="<?php echo $line['id_personne']; ?>" />

		<button type="submit" class="btn btn-success">Éditer le client</button>
	</form>
</div>