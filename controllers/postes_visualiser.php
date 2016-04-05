<div class="col-lg-9">
		<?php
		    // Check if the id is a number, and sanitize it
		    if(!is_numeric($_GET['id']))
		        die('Bad usage. $_GET[id] should be a number!');
		    else
		        $_GET['id'] = $slim->pdo->quote($_GET['id']);
		
		    // Query the database
		    $query = $slim->pdo->query('SELECT * FROM ' . $config['db_prefix'] . 'T_Poste WHERE num_poste = ' . $_GET['id']);
		
		    // Check if the id is valid
		    if($query->rowCount() < 1)
		        die('Nothing found');
		
		    $line = $query->fetch();
		
		    $templacat->set_variable("page_title", "Détails pour " . $line['libelle']);
		?>
		
		
		<div class="panel panel-default contenu-page">
		    <p><a href="postes_voir">« Retourner à la liste des postes</a></p>
		    <h1>Fiche poste <a class="btn btn-warning pull-right" href="postes_editer?id=<?php echo $line['num_poste']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le poste</a></h1>
		    
		    <table class="table">
		        <tr>
		            <td class="titre-tableau">Nom du poste</td>
		            <td><span class="glyphicon glyphicon-file"></span> <?php echo $line['libelle']; ?></td>
		        </tr>
		        <tr>
		            <td class="titre-tableau">Tarif horaire</td>
		            <td><span class="glyphicon glyphicon-euro"></span> <?php echo $line['tarif_horaire'] . ' ' . $config['currency']; ?></td>
		        </tr>
		        <tr>
		            <td class="titre-tableau">Part salariale</td>
		            <td><span class="glyphicon glyphicon-euro"></span> <?php echo $line['part_salariale'] . ' ' . $config['currency']; ?></td>
		        </tr>
		    </table>
		</div></div>
