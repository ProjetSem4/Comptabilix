				<?php
				$templacat->set_variable("page_title", "Ajouter les clients");
				?>
                
                <div class="panel panel-default contenu-page">
					<p><a href="clients_voir">« Retourner à la liste des clients</a></p>
                    <h1>Ajouter un client</h1>
                    
					<form class="" role="form">
					
						<h3>Informations sur l'entreprise</h3>
						
						<div class="form-group col-sm-12">
							<label for="rs">Raison sociale du client :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
								<input id="rs" type="text" class="form-control" placeholder="Raison sociale" required>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<label for="adresse">Adresse du client :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
								<input id="adresse" type="text" class="form-control" placeholder="Adresse" required>
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label for="cp">Code postal :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
								<input id="cp" type="text" class="form-control" placeholder="Code postal" required>
							</div>
						</div>
						<div class="form-group col-sm-8">
							<label for="email">Ville :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
								<input type="text" class="form-control" placeholder="Ville" required>
							</div>
						</div>
						
						<div class="form-group col-sm-6">
							<label for="email">Numéro de téléphone :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
								<input type="text" class="form-control" placeholder="Téléphone" required>
							</div>
						</div>
						<div class="form-group col-sm-6">
							<label for="email">Adresse e-mail :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
								<input type="email" class="form-control" placeholder="E-mail" required>
							</div>
						</div>
						
						<h3>Maîtrises d'ouvrage</h3>
						
						<table class="table">
							<tr>
								<th style="width: 50px">#</th>
								<th>Nom</th>
								<th style="width: 100px">Actions</th>
							</tr>
							<tr>
								<td>4</td>
								<td>Truc Machin</td>
								<td>
									<a class="btn btn-info" title="Visualiser le salarié" href="salaries_visualiser.php"><span class="glyphicon glyphicon-user"></span></a>
									<a class="btn btn-danger" title="Éditer le salarié" href="salaries_ajouter.php"><span class="glyphicon glyphicon-trash"></span></a>
								</td>
							</tr>
						</table>
						<a class="btn btn-default col-sm-6" title="Visualiser le projet" href="projet_visualiser.php">Ajouter un nouvel MOA</a>
						<a class="btn btn-default col-sm-6" title="Visualiser le projet" href="projet_visualiser.php">Ajouter un MOA existant</a>
						
						<button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
						<button type="submit" class="btn btn-success">Ajouter le client</button>
					</form>
					
                </div>
            