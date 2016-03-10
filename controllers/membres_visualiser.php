				<?php
				$templacat->set_variable("page_title", "Visualiser les membres");
				?>
                
                <div class="panel panel-default contenu-page">
					<p><a href="membres_voir.php">« Retourner à la liste des membres</a></p>
                    <h1>Fiche membre <a class="btn btn-warning pull-right" href="#"><span class="glyphicon glyphicon-pencil"></span> Éditer le membre</a></h1>
					<table class="table">
						<tr>
							<td class="titre-tableau">Nom</td>
							<td><span class="glyphicon glyphicon-user"></span> Quentin Bouteiller</td>
						</tr>
						<tr>
							<td class="titre-tableau">Titre</td>
							<td><span class="glyphicon glyphicon-king"></span> Vice-trésorier
						</tr>
						<tr>
							<td class="titre-tableau">Adresse</td>
							<td><span class="glyphicon glyphicon-home"></span> 1254 boulevard des fausses adresses</td>
						</tr>
						<tr>
							<td class="titre-tableau">Ville</td>
							<td><span class="glyphicon glyphicon-map-marker"></span> 75000 Paris</td>
						</tr>
						<tr>
							<td class="titre-tableau">Numéro de téléphone</td>
							<td><span class="glyphicon glyphicon-earphone"></span> 01.23.34.56.78</td>
						</tr>
						<tr>
							<td class="titre-tableau">Adresse e-mail</td>
							<td><span class="glyphicon glyphicon-envelope"></span> <a href="#">test@example.com</a></td>
						</tr>
					</table>
					
					<h3>Projets associés</h3>
					<table class="table">
						<tr>
							<th>#</th>
							<th>Titre du projet</th>
							<th>Rôle</th>
							<th>Rémunération</th>
							<th style="width: 50px">Action</th>
						</tr>
						<?php
							for($i = 5; $i > 0; $i--)
								echo '<tr>
									<td>' . $i . '</td>
									<td>Site web entreprise SignaNet</td>
									<td>Développement</td>
									<td>' . mt_rand(250, 500) . ',00€</td>
									<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser.php"><span class="glyphicon glyphicon-search"></span></a></tr>
								</tr>';
						?>
					</table>
                </div>
       
        