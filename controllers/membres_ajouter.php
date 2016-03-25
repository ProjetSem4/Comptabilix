<?php
	$templacat->set_variable("page_title", "Ajouter un membre");

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
	<p><a href="membres_voir">« Retourner à la liste des membres</a></p>
    <h1>Ajouter un membre</h1>
	
	<form class="" role="form" method="post" action="membres_ajouter_submit">
		<div class="form-group col-sm-6">
			<label for="prenom">Prénom : </label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span></div>
				<input id="prenom" name="prenom" type="text" class="form-control"  placeholder="Prénom" required>
			</div>
		</div>   

		<div class="form-group col-sm-6">
			<label for="nom">Nom : </label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span> </div>
				<input id="nom" name="nom" type="text" class="form-control" placeholder="Nom" required>
			</div>
		</div>

		<div class="form-group col-sm-8">
			<label for="titre">Titre dans l'association : </label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-king"> </span></div>
				<input id="titre" name="titre" type="text" class="form-control" placeholder="Titre dans l'association" required>
			</div>
		</div>
		<div class="form-group col-sm-4">
			<label for="actif">Est actif ?</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-thumbs-up"> </span></div>
				<select id="actif" name="actif" class="form-control" required>
					<option value="1">Oui</option>
					<option value="0">Non</option>
				</select>
			</div>
		</div>
   
		<div class="form-group col-sm-12">
			<label for="adresse">Adresse : </label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
				<input id="adresse" name="adresse" type="adress" class="form-control" placeholder="Adresse">
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
			<label for="ville">Ville :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
				<input id="ville" name="ville" type="text" class="form-control" placeholder="Ville" required>
			</div>
		</div>
   
		<div class="form-group col-sm-6">
			<label for="tel">Numéro de téléphone : </label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
				<input type="phone" name="tel" class="form-control" id="tel" placeholder="Téléphone">
			</div>
		</div>
   
		<div class="form-group col-sm-6">
			<label for="email">Adresse e-mail : </label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
				<input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
			</div>
		</div>
   		
   		<button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
		<button type="submit" class="btn btn-success">Ajouter le membre</button>  
   	</form>
</div>				   