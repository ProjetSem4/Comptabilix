<?php
    $templacat->set_variable("page_title", "Voir les paiements");
?>
<div class="panel panel-default contenu-page">
    <h1>Gestion des paiements <a class="btn btn-success pull-right" href="paiements_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un paiement</a></h1>
    <p>Retrouvez ici l'ensemble des paiements effectués à votre association.</p>
    
    <table class="table table-hover">
        <tr>
            <th style="width: 30px">#</th>
            <th>Date du paiement</th>
            <th>Montant versé</th>
            <th>Devis associé</th>
            <th>Client associé</th>
        </tr>
        <?php
            // Get the current page
            $page = 1;
            if(isset($_GET['page']) && $_GET['page'] > 0)
                $page = $_GET['page'];

            // The first row to get from the database
            $start_limit = ($page - 1) * $config['paiements_per_page'];

            // See how many paiements there is in the database
            $query_nbr_paiement = $slim->pdo->query('SELECT count(num_devis) as nb_paiements FROM ' . $config['db_prefix'] . 'TJ_Devis_Societe');
            $nbr_paiement = $query_nbr_paiement->fetch();
            $nbr_paiement = $nbr_paiement['nb_paiements'];

            // The see how many pages we have
            $number_of_pages = ceil($nbr_paiement / $config['paiements_per_page']);

            // Do the query
            $query = $slim->pdo->query('SELECT TDS.*, VS.raison_sociale, TP.titre_projet, TP.num_projet
                                        FROM ' . $config['db_prefix'] . 'TJ_Devis_Societe as TDS
                                        INNER JOIN ' . $config['db_prefix'] . 'T_Devis as TD
                                        ON TD.num_devis = TDS.num_devis
                                        INNER JOIN ' . $config['db_prefix'] . 'T_Projet AS TP
                                        ON TD.num_projet = TP.num_projet
                                        INNER JOIN ' . $config['db_prefix'] . 'V_Societe as VS
                                        ON TDS.id_societe = VS.id_personne
                                        ORDER BY date_paiement DESC
                                        LIMIT ' . $start_limit . ', ' . $config['paiements_per_page']);

            // If no paiement can be found
            if($query->rowCount() < 1)
                die('No paiements found');
            
            while($line = $query->fetch())
            {
                echo '<tr>
                        <td>n/a</td>
                        <td>' . date('d/m/Y', strtotime($line['date_paiement'])) . '</td>
                        <td>' . $line['quantite_payee'] . ' ' . $config['currency'] . '</td>
                        <td><a href="devis_visualiser?id=' . $line['num_devis'] . '">' . $line['num_devis'] . '</a> <a href="projet_visualiser?id=' . $line['num_projet'] . '">(' . $line['titre_projet'] . ')</a></td>
                        <td><a href="clients_visualiser?id=' . $line['id_societe'] . '">' . $line['raison_sociale'] . '</a></td>
                    </tr>';
            }
        ?>
        <tr>
            <th></th>
            <th colspan="5">Total : <?php echo $nbr_paiement; ?> paiements</th>
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