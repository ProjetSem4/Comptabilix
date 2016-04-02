<div class="col-lg-9">
		<?php
		    // Check if the id is a number, and sanitize it
		    if(!is_numeric($_GET['id']))
		        die('Bad usage. $_GET[id] should be a number!');
		    else
		        $_GET['id'] = $slim->pdo->quote($_GET['id']);
		
		    // Query the database
		    $query = $slim->pdo->query('SELECT * FROM ' . $config['db_prefix'] . 'T_Service WHERE num_service = ' . $_GET['id']);
		
		    // Check if the id is valid
		    if($query->rowCount() < 1)
		        die('Nothing found');
		
		    $line = $query->fetch();
		
		    $templacat->set_variable("page_title", "Détails pour " . $line['libelle']);
		?>
		
		
		<div class="panel panel-default contenu-page">
		    <p><a href="services_voir">« Retourner à la liste des services</a></p>
		    <h1>Fiche service <a class="btn btn-warning pull-right" href="services_editer?id=<?php echo $line['num_service']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le service</a></h1>
		    
		    <table class="table">
		        <tr>
		            <td class="titre-tableau">Nom du service</td>
		            <td><span class="glyphicon glyphicon-file"></span> <?php echo $line['libelle']; ?></td>
		        </tr>
		        <tr>
		            <td class="titre-tableau">Tarif mensuel</td>
		            <td><span class="glyphicon glyphicon-euro"></span> <?php echo $line['tarif_mensuel'] . ' ' . $config['currency']; ?></td>
		        </tr>
		    </table>
		</div></div>
