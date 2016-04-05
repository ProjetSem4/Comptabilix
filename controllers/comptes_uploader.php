<?php
    // Commençons par vérifier si l'utilisation est correcte
    if(!isset($_POST['annee'], $_FILES['fichier']) || !is_numeric($_POST['annee']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // On vérifie ensuite qu'il y a bien au moins un fichier de sélectionné
    elseif(empty($_FILES['fichier']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Vous devez sélectionner un fichier.');
    
    // Si tout va bien
    else
    {
        // On vérifie que les dossiers existent
        if(!is_dir('uploads/'))
            mkdir('uploads');

        if(!is_dir('uploads/' . $_POST['annee']))
            mkdir('uploads/' . $_POST['annee']);
        
        // Puis pour chaque fichier uploadé
        $nombre_fichiers = count($_FILES['fichier']['name']);

        for($i = 0; $i < $nombre_fichiers; $i++)
        {
            // S'il n'y a pas d'erreur
            if($_FILES['fichier']['error'][$i] === 0)
            {
                // Alors on le déplace dans le bon dossier
                rename($_FILES['fichier']['tmp_name'][$i], 'uploads/' . $_POST['annee'] . '/' . basename($_FILES['fichier']['name'][$i]) . '.' . time());
            }
            // Sinon on affiche un message d'erreur
            else
                $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Impossible d\'uploader « ' . htmlspecialchars($_FILES['fichier']['name'][$i]) . ' ».');

        }

        // Et enfin on affiche un message de succès
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le(s) fichier(s) a/ont été uploadé(s) avec succès.');
    }

    header('Location: comptes_voir');
?>