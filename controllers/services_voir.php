<div class="col-lg-9">
		<?php
		    $templacat->set_variable("page_title", "Voir les services");
		?>
		<div class="panel panel-default contenu-page">
		    <h1>Gestion des services <a class="btn btn-success pull-right" href="services_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un service</a></h1>
		    <p>Retrouvez ici l'ensemble des services proposés pour les projets de votre association.</p>
		    
		    <table class="table table-hover">
		        <tr>
		            <th style="width: 30px">#</th>
		            <th>Nom du service</th>
		            <th style="width: 200px">Tarif mensuel</th>
		            <th style="width: 100px">Actions</th>
		        </tr>
		        <?php
		            // Get the current page
		            $page = 1;
		            if(isset($_GET['page']) && $_GET['page'] > 0)
		                $page = $_GET['page'];
		
		            // The first row to get from the database
		            $start_limit = ($page - 1) * $config['services_per_page'];
		
		            // See how many services there is in the database
		            $query_nbr_service = $slim->pdo->query('SELECT count(num_service) as nb_services FROM ' . $config['db_prefix'] . 'T_Service');
		            $nbr_service = $query_nbr_service->fetch();
		            $nbr_service = $nbr_service['nb_services'];
		
		            // The see how many pages we have
		            $number_of_pages = ceil($nbr_service / $config['services_per_page']);
		
		            // Do the query
		            $query = $slim->pdo->query('SELECT num_service, libelle, tarif_mensuel
		                FROM ' . $config['db_prefix'] . 'T_Service
		                ORDER BY num_service DESC
		                LIMIT ' . $start_limit . ', ' . $config['services_per_page']);
		
		            // If no service can be found
		            if($query->rowCount() < 1)
		                die('No services found');
		            
		            while($line = $query->fetch())
		            {
		                echo '<tr>
		                        <td>' . $line['num_service'] . '</td>
		                        <td>' . $line['libelle'] . '</td>
		                        <td>' . $line['tarif_mensuel'] . ' ' . $config['currency'] . '</td>
		                        <td><a class="btn btn-info" title="Visualiser le service" href="services_visualiser?id=' . $line['num_service'] . '"><span class="glyphicon glyphicon-user"></span></a>
		                        <a class="btn btn-warning" title="Éditer le service" href="services_editer?id=' . $line['num_service'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
		                    </tr>';
		            }
		        ?>
		        <tr>
		            <th></th>
		            <th colspan="3">Total : <?php echo $nbr_service; ?> services</th>
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
