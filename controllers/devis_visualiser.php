<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['id']))
        die('Bad usage. $_GET[id] should be a number!');
    else
        $_GET['id'] = $slim->pdo->quote($_GET['id']);

    // Query the database
    $query = $slim->pdo->query('SELECT num_devis, date_emission, est_accepte, date_acceptation, ' . $config['db_prefix'] . 'T_Projet.num_projet, titre_projet, id_personne, raison_sociale
                                FROM ' . $config['db_prefix'] . 'T_Devis
                                INNER JOIN ' . $config['db_prefix'] . 'T_Projet
                                ON ' . $config['db_prefix'] . 'T_Devis.num_projet = ' . $config['db_prefix'] . 'T_Projet.num_projet
                                INNER JOIN ' . $config['db_prefix'] . 'V_Societe
                                ON ' . $config['db_prefix'] . 'T_Projet.id_societe = ' . $config['db_prefix'] . 'V_Societe.id_personne
                                WHERE ' . $config['db_prefix'] . 'T_Devis.num_devis = ' . $_GET['id']);

    // Check if the id is valid
    if($query->rowCount() < 1)
        die('Nothing found');

    $line = $query->fetch();

    $templacat->set_variable("page_title", "Devis n°" . $line['num_devis']);

    // Compute the cost of all the postes
    $query_calc_postes = $slim->pdo->query('SELECT sum(tarif_horaire * nbr_heures) as cout_total FROM TJ_Devis_Salarie_Poste INNER JOIN ' . $config['db_prefix'] . 'T_Poste ON TJ_Devis_Salarie_Poste.num_poste = ' . $config['db_prefix'] . 'T_Poste.num_poste WHERE num_devis = ' . $_GET['id']);
    $cout_postes = $query_calc_postes->fetch()['cout_total'];

    // Set a price if there is no result
    $cout_postes = ($cout_postes == '' ? '0.00' : $cout_postes);

    // Compute the cost of all the services
    $query_calc_services = $slim->pdo->query('SELECT sum(tarif_mensuel) as cout_total FROM TJ_Devis_Service INNER JOIN ' . $config['db_prefix'] . 'T_Service ON TJ_Devis_Service.num_service = ' . $config['db_prefix'] . 'T_Service.num_service WHERE num_devis = ' . $_GET['id']);
    $cout_services = $query_calc_services->fetch()['cout_total'];

    // Set a price if there is no result
    $cout_services = ($cout_services == '' ? '0.00' : $cout_services);

    // Show message(s), if needed
    if(isset($_SESSION['fortitudo_messages']) && is_array($_SESSION['fortitudo_messages']))
    {
        // For each message
        foreach($_SESSION['fortitudo_messages'] as $message)
        {
            // Use a different layout, determined by the type of the message
            switch($message['type'])
            {
                case 'error' : 
                    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message['content'] . '</div>';
                    break;
            
                case 'success' : 
                    echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message['content'] . '</div>';
                    break;
            }
        }

        // Clean the message queue
        $_SESSION['fortitudo_messages'] = array();
    }
?>


<div class="panel panel-default contenu-page">
    <p><a href="projet_visualiser?id=<?php echo $line['num_projet']; ?>">« Retourner à la fiche projet</a></p>
    <h1>
        Devis n°<?php echo $line['num_devis']; ?>
        <?php 
            if($line['est_accepte'] == 1)
                echo '<span class="label label-success">Validé</span>';
            else
                echo '<span class="label label-danger">Non validé</span> <a class="btn btn-success pull-right" href="devis_valider?id=' . $line['num_devis'] . '"><span class="glyphicon glyphicon-ok"></span> Valider le devis</a>';
        ?>
    </h1>
    
    <table class="table">
        <tr>
            <td class="titre-tableau">Date d'émission</td>
            <td><span class="glyphicon glyphicon-calendar"></span> <?php echo date('d/m/Y', strtotime($line['date_emission'])); ?></td>
        </tr>
        <?php
            if($line['est_accepte'] == 1)
                echo '<tr>
                        <td class="titre-tableau">Date d\'acceptation</td>
                        <td><span class="glyphicon glyphicon-calendar"></span> ' . $line['date_acceptation'] . '</td>
                    </tr>';
        ?>
        <tr>
            <td class="titre-tableau">Projet associé</td>
            <td><span class="glyphicon glyphicon-file"></span> <a href="projet_visualiser?id=<?php echo $line['num_projet']; ?>"><?php echo $line['titre_projet']; ?></a></td>
        </tr>
        <tr>
            <td class="titre-tableau">Entreprise cliente</td>
            <td><span class="glyphicon glyphicon-briefcase"></span> <a href="clients_visualiser?id=<?php echo $line['id_personne']; ?>"><?php echo $line['raison_sociale']; ?></a></td>
        </tr>
        <tr>
            <td class="titre-tableau">Coût des postes</td>
            <td><span class="glyphicon glyphicon-euro"></span> <?php echo $cout_postes . ' ' . $config['currency']; ?></td>
        </tr>
        <tr>
            <td class="titre-tableau">Coût des services</td>
            <td><span class="glyphicon glyphicon-euro"></span> <?php echo $cout_services . ' ' . $config['currency']; ?>/mois</td>
        </tr>
    </table>

    <h3>Postes associés</h3>
    <table class="table">
        <?php
            // Get all the postes corresponding to the project
            $query_select_postes = $slim->pdo->query('SELECT *
                                                        FROM TJ_Devis_Salarie_Poste
                                                        INNER JOIN ' . $config['db_prefix'] . 'T_Poste
                                                        ON TJ_Devis_Salarie_Poste.num_poste = ' . $config['db_prefix'] . 'T_Poste.num_poste
                                                        INNER JOIN ' . $config['db_prefix'] . 'V_Salarie
                                                        ON TJ_Devis_Salarie_Poste.id_personne = ' . $config['db_prefix'] . 'V_Salarie.id_personne
                                                        WHERE num_devis = ' . $_GET['id']);

            // If there is at least one poste
            if($query_select_postes->rowCount() > 0)
            {
                echo '<tr><th>#</th><th>Nom du poste</th><th>Tarif horaire</th><th>Nombre d\'heures</th><th>Coût total</th><th>Salarié</th></tr>';

                while($ligne_poste = $query_select_postes->fetch())
                    echo '<tr>
                            <td>' . $ligne_poste['num_poste'] . '</td>
                            <td>' . $ligne_poste['libelle'] . '</td>
                            <td>' . $ligne_poste['tarif_horaire'] . ' '  . $config['currency'] . '</td>
                            <td>' . $ligne_poste['nbr_heures'] . '</td>
                            <td>' . $ligne_poste['nbr_heures'] * $ligne_poste['tarif_horaire']. ' '  . $config['currency'] . '</td>
                            <td>' . $ligne_poste['prenom'] . ' ' . $ligne_poste['nom'] . '</td>
                            </td>
                        </tr>';   
            }
            else
                echo '<tr><td colspan="6" style="text-align:center">Aucun poste associé n\'a pu être trouvé</td></td>';
        ?>
    </table>

    <h3>Services associés</h3>
    <table class="table">
        <?php        
            $query_select_services = $slim->pdo->query('SELECT *
                                                        FROM TJ_Devis_Service
                                                        INNER JOIN ' . $config['db_prefix'] . 'T_Service
                                                        ON TJ_Devis_Service.num_service = ' . $config['db_prefix'] . 'T_Service.num_service
                                                        WHERE num_devis = ' . $_GET['id']);
            
            // If there is at least one poste
            if($query_select_services->rowCount() > 0)
            {
                echo '<tr><th>#</th><th>Nom du service</th><th>Tarif mensuel</th><th>Date de début</th><th>Date de fin</th><th>Action</th></tr>';

                while($ligne_service = $query_select_services->fetch())
                    echo '<tr>
                        <td>' . $ligne_service['num_service'] . '</td>
                        <td>' . $ligne_service['libelle'] . '</td>
                        <td>' . $ligne_service['tarif_mensuel'] . ' ' . $config['currency'] . '</td>
                        <td>' . date('d/m/Y', strtotime($ligne_service['date_debut'])) . '</td>
                        <td>' . (($ligne_service['date_fin'] == '') ? 'n/a' : date('d/m/Y', strtotime($ligne_service['date_fin']))) . '</td>
                        <td><a class="btn btn-danger" title="Mettre fin au service" href="devis_fin_service?did=' . $line['num_devis'] . '&sid=' . $ligne_service['num_service'] . '"><span class="glyphicon glyphicon-remove"></span></a>
                        </td>
                    </tr>';
            }
            else
                echo '<tr><td colspan="4" style="text-align:center">Aucun service associé n\'a pu être trouvé</td></td>';
        ?>
    </table>
    <h3>Paiements effectués</h3>
    <table class="table">
        <?php
            // Get all the « paiements » corresponding to the project
            $query_select_revenues = $slim->pdo->query('SELECT *
                                                        FROM TJ_Devis_Societe
                                                        WHERE num_devis = ' . $_GET['id']);

            // If there is at least one poste
            if($query_select_revenues->rowCount() > 0)
            {
                echo '<tr><th>#</th><th>Date du paiement</th><th>Quantité payée</th></tr>';

                while($ligne_revenue = $query_select_revenues->fetch())
                    echo '<tr>
                            <td>n/a</td>
                            <td>' . date('d/m/Y', strtotime($ligne_revenue['date_paiement'])) . '</td>
                            <td>' . $ligne_revenue['quantite_payee'] . ' ' . $config['currency'] . '</td>
                            </td>
                        </tr>';   
            }
            else
                echo '<tr><td colspan="6" style="text-align:center">Aucun paiement associé n\'a pu être trouvé</td></td>';
        ?>
    </table>
</div>