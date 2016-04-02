<?php
    $templacat->set_variable("page_title", "Voir les salariés");
?>

<div class="panel panel-default contenu-page">
    <h1>Gestion des salariés <a class="btn btn-success pull-right" href="salaries_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un salarié</a></h1>
    <p>Retrouvez ici l'ensemble des salariés de votre association.</p>
    
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
            $start_limit = ($page - 1) * $config['salaries_per_page'];

            // See how many salariés there is in the database
            $query_nbr_salarie = $slim->pdo->query('SELECT count(id_personne) as nb_personnes FROM ' . $config['db_prefix'] . 'V_Salarie');
            $nbr_salarie = $query_nbr_salarie->fetch();
            $nbr_salarie = $nbr_salarie['nb_personnes'];

            // The see how many pages we have
            $number_of_pages = ceil($nbr_salarie / $config['salaries_per_page']);

            // Do the query
            $query = $slim->pdo->query('SELECT ' . $config['db_prefix'] . 'V_Salarie.id_personne, nom, prenom, count(T_Devis.num_devis) as nb_projets
                FROM ' . $config['db_prefix'] . 'V_Salarie
                LEFT JOIN ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste ON ' . $config['db_prefix'] . 'V_Salarie.id_personne = ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.id_personne
                LEFT JOIN ' . $config['db_prefix'] . 'T_Devis ON ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste.num_devis = ' . $config['db_prefix'] . 'T_Devis.num_devis
                WHERE ' . $config['db_prefix'] . 'T_Devis.est_accepte = 1 OR ' . $config['db_prefix'] . 'T_Devis.est_accepte IS NULL
                GROUP BY id_personne
                ORDER BY id_personne DESC
                LIMIT ' . $start_limit . ', ' . $config['salaries_per_page']);

            // If no salarie can be found
            if($query->rowCount() < 1)
                die('No salariés found');
            
            while($line = $query->fetch())
            {
                echo '<tr>
                        <td>' . $line['id_personne'] . '</td>
                        <td>' . $line['prenom'] . ' ' . $line['nom'] . '</td>
                        <td>' . $line['nb_projets'] . '</td>
                        <td><a class="btn btn-info" title="Visualiser le salarie" href="salaries_visualiser?id=' . $line['id_personne'] . '"><span class="glyphicon glyphicon-user"></span></a>
                        <a class="btn btn-warning" title="Éditer le salarie" href="salaries_editer?id=' . $line['id_personne'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    </tr>';
            }
        ?>
        <tr>
            <th></th>
            <th colspan="3">Total : <?php echo $nbr_salarie; ?> salariés</th>
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