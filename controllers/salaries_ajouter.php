				<?php
				$templacat->set_variable("page_title", "Ajouter les salariés");
				?>
                
                <div class="panel panel-default contenu-page">
					<p><a href="salaries_voir.php">« Retourner à la liste des salariés</a></p>
                    <h1>Ajouter un salarié</h1>
                    
					<form class="" role="form">
						<div class="form-group col-sm-6">
							<label for="nom">Nom du salarié :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								<input id="nom" type="text" class="form-control" placeholder="Nom" required>
							</div>
						</div>
						
						<div class="form-group col-sm-6">
							<label for="prenom">Prénom du salarié :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								<input id="prenom" type="text" class="form-control" placeholder="Prénom" required>
							</div>
						</div>
						<div class="form-group col-sm-12">
							<label for="adresse">Adresse du salarié :</label>
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
						
						<button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
						<button type="submit" class="btn btn-success">Ajouter le salarié</button>
					</form>
					
                </div>
            
        