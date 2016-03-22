<?php
	$templacat->set_variable("page_title", "Ajouter les clients");
?>            
<div class="panel panel-default contenu-page">
	<p><a href="clients_voir.php">« Retourner à la liste des clients</a></p>
    <h1>Ajouter un client</h1>
                    
	<form class="" role="form" method="post" action="clients_ajouter_submit">
		<h3>Informations sur l'entreprise</h3>
		
		<div class="form-group col-sm-12">
			<label for="rs">Raison sociale du client :</label>
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
				<input id="rs" name="rs" type="text" class="form-control" placeholder="Raison sociale" required>
			</div>
		</div>
		
		<div class="form-group col-sm-12">
			<label for="adresse">Adresse du client :</label>
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
		<button type="submit" class="btn btn-success">Ajouter le client</button>
	</form>
</div>