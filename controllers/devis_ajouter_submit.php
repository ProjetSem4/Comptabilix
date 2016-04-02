<?php
    $check_if_ok = false;

    // Check if all the required data are passed (correctly)
    if(!isset($_GET['pid']) || !is_numeric($_GET['pid']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du lien.');

    // Check if there is at least one poste or one service
    elseif(
        (!isset($_SESSION['tmp']['devis_services_' . $_GET['pid']]) && !isset($_SESSION['tmp']['devis_postes_' . $_GET['pid']])) ||
        ((count($_SESSION['tmp']['devis_services_' . $_GET['pid']]) + count($_SESSION['tmp']['devis_postes_' . $_GET['pid']])) < 1)
    )
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Vous devez ajouter au moins un poste ou un service au devis.');

    // If everything's good
    else
    {
        // Start by inserting a new devis
        $query_insert_devis = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'T_Devis (date_emission, est_accepte, date_acceptation, num_projet) VALUES (:de, 0, NULL, :np)');

        $query_insert_devis->bindParam(':de', date('Y-m-d'));
        $query_insert_devis->bindParam(':np', $_GET['pid']);

        $query_insert_devis->execute();

        // Now get the id of newly inserted line
        $query_id_new_devis = $slim->pdo->query('SELECT max(num_devis) as nid FROM ' . $config['db_prefix'] . 'T_Devis WHERE num_projet = ' . $slim->pdo->quote($_GET['pid']));
        $devis_id = $query_id_new_devis->fetch()['nid'];

        // Then insert each poste (if any)
        if(isset($_SESSION['tmp']['devis_postes_' . $_GET['pid']]))
        {
            foreach($_SESSION['tmp']['devis_postes_' . $_GET['pid']] as $poste)
            {
                $query_insert_devis_poste = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste VALUES (:idp, :idd, :ids, :nbh)');

                $query_insert_devis_poste->bindParam(':idp', $poste['id_poste']);
                $query_insert_devis_poste->bindParam(':idd', $devis_id);
                $query_insert_devis_poste->bindParam(':ids', $poste['id_salarie']);
                $query_insert_devis_poste->bindParam(':nbh', $poste['nb_heures']);

                $query_insert_devis_poste->execute();
            }

            unset($_SESSION['tmp']['devis_postes_' . $_GET['pid']]);
        }

        // Same for each service (if any)
        if(isset($_SESSION['tmp']['devis_services_' . $_GET['pid']]))
        {
            foreach($_SESSION['tmp']['devis_services_' . $_GET['pid']] as $service)
            {
                $query_insert_devis_service = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'TJ_Devis_Service VALUES (:idd, :ids, :dd, :df)');

                $query_insert_devis_service->bindParam(':idd', $devis_id);
                $query_insert_devis_service->bindParam(':ids', $service['id_service']);
                $query_insert_devis_service->bindParam(':dd', date('Y-m-d', $service['date_debut']));
                $query_insert_devis_service->bindParam(':df', ($service['date_fin'] == '' ? null : date('Y-m-d', $service['date_fin'])));

                $query_insert_devis_service->execute();
            }

            unset($_SESSION['tmp']['devis_services_' . $_GET['pid']]);
        }
        
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Devis ajouté avec succès');

        $check_if_ok = true;
    }

    if(!$check_if_ok)
        header('Location: devis_ajouter?pid=' . $_GET['pid']);
    else
        header('Location: projet_visualiser?id=' . $_GET['pid']);
?>