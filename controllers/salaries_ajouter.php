<?php
	$templacat->set_variable("page_title", "Ajouter un salarié");

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
	<p><a href="salaries_voir">« Retourner à la liste des salariés</a></p>
    <h1>Ajouter un salarié</h1>
                    
	<form class="" role="form" method="post" action="salaries_ajouter_submit">
		<div class="form-group col-sm-6">
			<label for="nom">Nom du salarié :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
				<input id="nom" name="nom" type="text" class="form-control" placeholder="Nom" required>
			</div>
		</div>
						
		<div class="form-group col-sm-6">
			<label for="prenom">Prénom du salarié :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
				<input id="prenom" name="prenom" type="text" class="form-control" placeholder="Prénom" required>
			</div>
		</div>
		<div class="form-group col-sm-12">
			<label for="adresse">Adresse du salarié :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
				<input id="adresse" name="adresse" type="text" class="form-control" placeholder="Adresse" required>
			</div>
		</div>
		<div class="form-group col-sm-4">
			<label for="cp">Code postal :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
				<input id="cp" name="cp" type="text" class="form-control" placeholder="Code postal" required>
			</div>
		</div>
		<div class="form-group col-sm-8">
			<label for="email">Ville :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
				<input type="text" name="ville" class="form-control" placeholder="Ville" required>
			</div>
		</div>
						
		<div class="form-group col-sm-6">
			<label for="email">Numéro de téléphone :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
				<input type="text" name="tel" class="form-control" placeholder="Téléphone" required>
			</div>
		</div>
		
		<div class="form-group col-sm-6">
			<label for="email">Adresse e-mail :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
				<input type="email" name="email" class="form-control" placeholder="E-mail" required>
			</div>
		</div>
						
		<button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
		<button type="submit" class="btn btn-success">Ajouter le salarié</button>
	</form>					
</div>