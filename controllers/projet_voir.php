<?php
	$templacat->set_variable("page_title", "Voir les projets");
?>
<div class="panel panel-default contenu-page">
    <h1>Gestion des projets <a class="btn btn-success pull-right" href="projet_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un projet</a></h1>
    <p>Retrouvez ici l'ensemble des projets de votre association.</p>
    
    <table class="table table-hover">
        <tr>
            <th style="width: 30px">#</th>
            <th>Nom du projet</th>
            <th style="width: 200px">Client</th>
            <th style="width: 100px">Actions</th>
        </tr>
        <?php
            // Get the current page
            $page = 1;
            if(isset($_GET['page']) && $_GET['page'] > 0)
                $page = $_GET['page'];

            // The first row to get from the database
            $start_limit = ($page - 1) * $config['projets_per_page'];

            // See how many projets there is in the database
            $query_nbr_projet = $slim->pdo->query('SELECT count(num_projet) as nb_projets FROM ' . $config['db_prefix'] . 'T_Projet');
            $nbr_projet = $query_nbr_projet->fetch();
            $nbr_projet = $nbr_projet['nb_projets'];

            // The see how many pages we have
            $number_of_pages = ceil($nbr_projet / $config['projets_per_page']);

            // Do the query
            $query = $slim->pdo->query('SELECT ' . $config['db_prefix'] . 'T_Projet.num_projet, ' . $config['db_prefix'] . 'T_Projet.titre_projet, ' . $config['db_prefix'] . 'V_Societe.id_personne, ' . $config['db_prefix'] . 'V_Societe.raison_sociale
                FROM ' . $config['db_prefix'] . 'T_Projet
                INNER JOIN ' . $config['db_prefix'] . 'V_Societe ON ' . $config['db_prefix'] . 'T_Projet.id_societe = ' . $config['db_prefix'] . 'V_Societe.id_personne
                ORDER BY num_projet DESC
                LIMIT ' . $start_limit . ', ' . $config['projets_per_page']);

            // If no projet can be found
            if($query->rowCount() < 1)
                die('No projets found');
            
            while($line = $query->fetch())
            {
                echo '<tr>
                        <td>' . $line['num_projet'] . '</td>
                        <td>' . $line['titre_projet'] . '</td>
                        <td><a href="clients_visualiser?id=' . $line['id_personne'] . '">' . $line['raison_sociale'] . '</a></td>
                        <td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser?id=' . $line['num_projet'] . '"><span class="glyphicon glyphicon-user"></span></a>
                        <a class="btn btn-warning" title="Éditer le projet" href="projet_editer?id=' . $line['num_projet'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    </tr>';
            }
        ?>
        <tr>
            <th></th>
            <th colspan="3">Total : <?php echo $nbr_projet; ?> projets</th>
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
</div>