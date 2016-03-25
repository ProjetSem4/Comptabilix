<?php
	$templacat->set_variable("page_title", "Voir les membres");
?>
<div class="panel panel-default contenu-page">
    <h1>Gestion des membres <a class="btn btn-success pull-right" href="membres_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un membre</a></h1>
    <p>Retrouvez ici l'ensemble des membres de votre association.</p>
                    
    <table class="table table-hover">
        <tr>
            <th style="width: 30px">#</th>
            <th>Nom</th>
            <th style="width: 150px">Projets</th>
            <th style="width: 100px">Actions</th>
        </tr>
        <?php
            // Get the current page
            $page = 1;
            if(isset($_GET['page']) && $_GET['page'] > 0)
                $page = $_GET['page'];

            // The first row to get from the database
            $start_limit = ($page - 1) * $config['membres_per_page'];

            // See how many membres there is in the database
            $query_nbr_membre = $slim->pdo->query('SELECT count(id_personne) as nb_personnes FROM V_Membre');
            $nbr_membre = $query_nbr_membre->fetch();
            $nbr_membre = $nbr_membre['nb_personnes'];

            // The see how many pages we have
            $number_of_pages = ceil($nbr_membre / $config['membres_per_page']);

            // Do the query
            $query = $slim->pdo->query('SELECT id_personne, nom, prenom, COUNT(num_projet) as nb_projets
                FROM V_Membre
                LEFT JOIN TJ_Membre_Projet ON V_Membre.id_personne = TJ_Membre_Projet.id_membre
                GROUP BY id_personne
                ORDER BY id_personne DESC
                LIMIT ' . $start_limit . ', ' . $config['membres_per_page']);

            // If no membre can be found
            if($query->rowCount() < 1)
                die('No membres found');
            
            while($line = $query->fetch())
            {
                echo '<tr>
                        <td>' . $line['id_personne'] . '</td>
                        <td>' . $line['prenom'] . ' ' . $line['nom'] . '</td>
                        <td>' . $line['nb_projets'] . '</td>
                        <td><a class="btn btn-info" title="Visualiser le membre" href="membres_visualiser?id=' . $line['id_personne'] . '"><span class="glyphicon glyphicon-user"></span></a>
                        <a class="btn btn-warning" title="Éditer le membre" href="membres_editer?id=' . $line['id_personne'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    </tr>';
            }
        ?>
        <tr>
            <th></th>
            <th colspan="3">Total : <?php echo $nbr_membre; ?> membres</th>
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