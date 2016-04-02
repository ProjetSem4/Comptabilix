<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_GET['did'], $_GET['sid']) || !is_numeric($_GET['did']) || !is_numeric($_GET['sid']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du lien.');

    // If everything's good
    else
    {
        $query_update_devis_service = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'TJ_Devis_Service SET date_fin = :df WHERE num_devis = :nd AND num_service = :ns');
        $query_update_devis_service->bindParam(':df', date('Y-m-d'));
        $query_update_devis_service->bindParam(':nd', $_GET['did']);
        $query_update_devis_service->bindParam(':ns', $_GET['sid']);
        $query_update_devis_service->execute();

        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le service a pris fin avec succès avec succès.');
    }

    header('Location: devis_visualiser?id=' . $_GET['did']);
?>