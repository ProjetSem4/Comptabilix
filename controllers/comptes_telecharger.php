<?php
    // Commençons par vérifier si l'utilisation est correcte
    if(!isset($_GET['a'], $_GET['fichier']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du générateur.');
    
    // L'année est-elle bien un nombre?
    elseif(!is_numeric($_GET['a']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'année doit être un nombre.');

    // Le fichier existe-t-il bien, dans le bon répertoire?
    elseif(!file_exists('uploads/' . $_GET['a'] . '/' . basename($_GET['fichier'])))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le fichier n\'existe pas.');

    // Ou si tout va bien
    else
    {
        // Chemin du fichier
        $fichier = 'uploads/' . $_GET['a'] . '/' . basename($_GET['fichier']);

        // On met les headers de téléchargement
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($_GET['fichier']) . '"'); 
        header('Content-Length: ' . filesize($fichier));
  
        // On output le contenu du fichier
        readfile($fichier);      

        // Et on quitte
        exit;
    }
    
    header('Location: comptes_voir');
?>