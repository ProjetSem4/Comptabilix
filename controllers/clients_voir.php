<div class="col-lg-9">
		<?php
		    $templacat->set_variable("page_title", "Voir les clients");
		?>
		<!--<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Succès : </strong>le client « SignaNet » a correctement été ajouté!
		</div>
		
		<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Succès : </strong>le client « SignaNet » a correctement été édité!
		</div>
		
		<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Succès : </strong>le client « SignaNet » a correctement été supprimé!
		</div>
		
		<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Erreur : </strong>impossible de supprimer « Truc Muche » puisqu'il est associé à au moins un projet!
		</div>-->
		
		<div class="panel panel-default contenu-page">
		    <h1>Gestion des clients <a class="btn btn-success pull-right" href="clients_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un client</a></h1>
		    <p>Retrouvez ici l'ensemble des clients de votre association.</p>
		
		    <table class="table table-hover">
		        <tr>
		            <th style="width: 30px">#</th>
		            <th>Raison sociale</th>
		            <th style="width: 150px">Projets</th>
		            <th style="width: 100px">Actions</th>
		        </tr>
		        <?php
		            // Get the current page
		            $page = 1;
		            if(isset($_GET['page']) && $_GET['page'] > 0)
		                $page = $_GET['page'];
		
		            // The first row to get from the database
		            $start_limit = ($page - 1) * $config['clients_per_page'];
		
		            // See how many clients there is in the database
		            $query_nbr_client = $slim->pdo->query('SELECT count(id_personne) as nb_personnes FROM ' . $config['db_prefix'] . 'V_Societe');
		            $nbr_client = $query_nbr_client->fetch();
		            $nbr_client = $nbr_client['nb_personnes'];
		
		            // The see how many pages we have
		            $number_of_pages = ceil($nbr_client / $config['clients_per_page']);
		
		            // Do the query
		            $query = $slim->pdo->query('
		                SELECT id_personne, raison_sociale, COUNT(num_projet) as nb_projets
		                FROM ' . $config['db_prefix'] . 'V_Societe
		                LEFT JOIN ' . $config['db_prefix'] . 'T_Projet ON ' . $config['db_prefix'] . 'V_Societe.id_personne = ' . $config['db_prefix'] . 'T_Projet.id_societe
		                GROUP BY id_personne
		                ORDER BY id_personne DESC
		                LIMIT ' . $start_limit . ', ' . $config['clients_per_page']);
		
		            // If no client can be found
		            if($query->rowCount() < 1)
		                die('No clients found');
		            
		            while($line = $query->fetch())
		            {
		                echo '<tr>
		                        <td>' . $line['id_personne'] . '</td>
		                        <td>' . $line['raison_sociale'] . '</td>
		                        <td>' . $line['nb_projets'] . '</td>
		                        <td><a class="btn btn-info" title="Visualiser le client" href="clients_visualiser?id=' . $line['id_personne'] . '"><span class="glyphicon glyphicon-user"></span></a>
		                        <a class="btn btn-warning" title="Éditer le client" href="clients_editer?id=' . $line['id_personne'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
		                    </tr>';
		            }
		        ?>
		        <tr>
		            <th></th>
		            <th colspan="3">Total : <?php echo $nbr_client; ?> clients</th>
		        </tr>
		    </table>
		</div>
		<div class="text-center">
		    <ul class="pagination">
		        <?php
		            // Create the pagination
		            for($i = 1; $i <= $number_of_pages; $i++)
		            {
		                if($i == $page) // If the page is the current one
		                    echo '<li class="active"><a href="?page=' . $i . '">' . $i . '</a></li>';
		                else
		                    echo '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
		            }
		        ?>
		    </ul>
		</div></div>
