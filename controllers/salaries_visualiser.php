<div class="col-lg-9">
		 <?php
			// Check if the id is a number, and sanitize it
			if(!is_numeric($_GET['id']))
				die('Bad usage. $_GET[id] should be a number!');
			else
				$_GET['id'] = $slim->pdo->quote($_GET['id']);
		
			// Query the database
			$query = $slim->pdo->query('SELECT * FROM ' . $config['db_prefix'] . 'V_Salarie WHERE id_personne = ' . $_GET['id']);
		
			// Check if the id is valid
			if($query->rowCount() < 1)
				die('Nothing found');
		
			$line = $query->fetch();
		
			$templacat->set_variable("page_title", "Détails pour " . $line['prenom'] . ' ' . $line['nom']);
		?>
		
		<div class="panel panel-default contenu-page">
			<p><a href="salaries_voir">« Retourner à la liste des salariés</a></p>
		    <h1>Fiche salarié <a class="btn btn-warning pull-right" href="salaries_editer?id=<?php echo $line['id_personne']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le salarié</a></h1>
			<table class="table">
				<tr>
					<td class="titre-tableau">Nom</td>
					<td><span class="glyphicon glyphicon-user"></span> <?php echo $line['prenom'] . ' ' . $line['nom']; ?></td>
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
		
			<h3>Projets associés</h3>
			<table class="table">
				<?php
					// List all the projets created for the corresponding salarie
					$query_projects = $slim->pdo->query('SELECT
							T_Projet.num_projet,
							T_Projet.titre_projet, 
							T_Poste.libelle, 
							T_Poste.tarif_horaire * ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.nbr_heures AS paie
						FROM ' . $config['db_prefix'] . 'V_Salarie
						INNER JOIN ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste ON ' . $config['db_prefix'] . 'V_Salarie.id_personne = ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.id_personne
						INNER JOIN ' . $config['db_prefix'] . 'T_Devis ON ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.num_devis = ' . $config['db_prefix'] . 'T_Devis.num_devis
						INNER JOIN ' . $config['db_prefix'] . 'T_Poste ON ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.num_poste = ' . $config['db_prefix'] . 'T_Poste.num_poste
						INNER JOIN ' . $config['db_prefix'] . 'T_Projet ON ' . $config['db_prefix'] . 'T_Devis.num_projet = ' . $config['db_prefix'] . 'T_Projet.num_projet
						WHERE ' . $config['db_prefix'] . 'T_Devis.est_accepte = 1 AND ' . $config['db_prefix'] . 'V_Salarie.id_personne = ' . $_GET['id'] . '
						ORDER BY ' . $config['db_prefix'] . 'T_Devis.date_acceptation DESC
					');
		
					// If none is found
					if($query_projects->rowCount() < 1)
						echo '<tr><td colspan="5" style="text-align:center">Aucun projet associé n\'a pu être trouvé</td></td>';
					
					// Otherwise
					else
					{
						echo '<tr><th>#</th><th>Titre du projet</th><th>Rôle</th><th>Rémunération</th><th style="width: 50px">Action</th></tr>';
		
						while($projet = $query_projects->fetch())
						{
							echo '<tr>
								<td>' . $projet['num_projet'] . '</td>
								<td>' . $projet['titre_projet'] . '</td>
								<td>' . $projet['libelle'] . '</td>
								<td>' . $projet['paie'] . ' ' . $config['currency'] . '</td>
								<td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser?id=' . $projet['num_projet'] . '"><span class="glyphicon glyphicon-search"></span></a></tr>
							</tr>';
						}
					}
				?>
			</table>
			</table>
		</div></div>
