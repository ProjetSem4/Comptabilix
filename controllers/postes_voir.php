<div class="col-lg-9">
		<?php
		    $templacat->set_variable("page_title", "Voir les postes");
		?>
		<div class="panel panel-default contenu-page">
		    <h1>Gestion des postes <a class="btn btn-success pull-right" href="postes_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un poste</a></h1>
		    <p>Retrouvez ici l'ensemble des postes proposés pour les projets de votre association.</p>
		    
		    <table class="table table-hover">
		        <tr>
		            <th style="width: 30px">#</th>
		            <th>Nom du poste</th>
		            <th>Tarif horaire</th>
		            <th>Part salariale</th>
		            <th style="width: 100px">Actions</th>
		        </tr>
		        <?php
		            // Get the current page
		            $page = 1;
		            if(isset($_GET['page']) && $_GET['page'] > 0)
		                $page = $_GET['page'];
		
		            // The first row to get from the database
		            $start_limit = ($page - 1) * $config['postes_per_page'];
		
		            // See how many postes there is in the database
		            $query_nbr_poste = $slim->pdo->query('SELECT count(num_poste) as nb_postes FROM ' . $config['db_prefix'] . 'T_Poste');
		            $nbr_poste = $query_nbr_poste->fetch();
		            $nbr_poste = $nbr_poste['nb_postes'];
		
		            // The see how many pages we have
		            $number_of_pages = ceil($nbr_poste / $config['postes_per_page']);
		
		            // Do the query
		            $query = $slim->pdo->query('SELECT num_poste, libelle, tarif_horaire, part_salariale
		                FROM ' . $config['db_prefix'] . 'T_Poste
		                ORDER BY num_poste DESC
		                LIMIT ' . $start_limit . ', ' . $config['postes_per_page']);
		
		            // If no poste can be found
		            if($query->rowCount() < 1)
		                die('No postes found');
		            
		            while($line = $query->fetch())
		            {
		                echo '<tr>
		                        <td>' . $line['num_poste'] . '</td>
		                        <td>' . $line['libelle'] . '</td>
		                        <td>' . $line['tarif_horaire'] . ' ' . $config['currency'] . '</td>
		                        <td>' . $line['part_salariale'] . ' ' . $config['currency'] . '</td>
		                        <td><a class="btn btn-info" title="Visualiser le poste" href="postes_visualiser?id=' . $line['num_poste'] . '"><span class="glyphicon glyphicon-user"></span></a>
		                        <a class="btn btn-warning" title="Éditer le poste" href="postes_editer?id=' . $line['num_poste'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
		                    </tr>';
		            }
		        ?>
		        <tr>
		            <th></th>
		            <th colspan="3">Total : <?php echo $nbr_poste; ?> postes</th>
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
