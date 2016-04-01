<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_GET['pid'], $_GET['id']) || is_numeric($_GET['pid']) == false || !is_numeric($_GET['id']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du lien.');

    else
    {
        if(isset($_SESSION['tmp']['devis_services_' . $_GET['pid']][$_GET['id']]))
        {
            unset($_SESSION['tmp']['devis_services_' . $_GET['pid']][$_GET['id']]);
            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le service a été supprimé avec succès.');
        }
        else
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le service n\'existe pas.');
    }

    header('Location: devis_ajouter?pid=' . $_GET['pid']);
?>