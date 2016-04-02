<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['service'], $_POST['date_debut'], $_POST['date_fin'], $_POST['num_projet']) || is_numeric($_POST['service']) == false || !is_numeric($_POST['num_projet']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['service']) || empty($_POST['date_debut']) || empty($_POST['num_projet']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');
    
    // And finally, check if dates are correct
    elseif(strtotime($_POST['date_debut']) === false)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La date de début est invalide.');
    
    elseif($_POST['date_fin'] != '' && strtotime($_POST['date_fin']) === false)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La date de fin est invalide.');

    elseif($_POST['date_fin'] != '' && strtotime($_POST['date_fin']) < strtotime($_POST['date_debut']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La date de fin ne peut être inférieure à la date de début.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST
        $_POST = clean_post($_POST);

        // If our temporary array isn't set, we create it
        if(!isset($_SESSION['tmp']['devis_services_' . $_POST['num_projet']]) || !is_array($_SESSION['tmp']['devis_services_' . $_POST['num_projet']]))
            $_SESSION['tmp']['devis_services_' . $_POST['num_projet']] = array();

        // Check if a corresponding line already exists (same service)
        $blocked = false;
        foreach($_SESSION['tmp']['devis_services_' . $_POST['num_projet']] as $id_ligne => $ligne_projet)
        {
            if($ligne_projet['id_service'] == $_POST['service'])
            {
                $blocked = true;
                $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le service a déjà été ajouté.');
            }
        }

        // If none has been found
        if($blocked == false)
        {
            // Store 
            $_SESSION['tmp']['devis_services_' . $_POST['num_projet']][] = array
                (
                    'id_service' => $_POST['service'],
                    'date_debut' => strtotime(str_replace('/', '-', $_POST['date_debut'])), // We replace « / » by « - » to fix french dates notation
                    'date_fin' => strtotime(str_replace('/', '-', $_POST['date_fin']))
                );
        
            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le service a été ajouté avec succès.');
        }
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
        {
            if(is_array($value))
            {
                foreach($value as $subkey => $subvalue)
                {
                    $cleaned_post_data[$key][$subkey] = htmlspecialchars($subvalue);
                }
            }
            else
                $cleaned_post_data[$key] = htmlspecialchars($value);
        }

        return $cleaned_post_data;
    }

    header('Location: devis_ajouter?pid=' . $_POST['num_projet']);
?>