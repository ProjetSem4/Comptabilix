<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['poste'], $_POST['salarie'], $_POST['nb_heures'], $_POST['num_projet']) || is_numeric($_POST['poste']) == false || !is_numeric($_POST['salarie']) || !is_numeric($_POST['nb_heures']) || !is_numeric($_POST['num_projet']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['poste']) || empty($_POST['salarie']) || empty($_POST['nb_heures']) || empty($_POST['num_projet']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');
    
    // And finally, check if the tarif is > 0
    elseif($_POST['nb_heures'] <= 0)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le nombre d\'heures est trop bas.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST
        $_POST = clean_post($_POST);

        // If our temporary array isn't set, we create it
        if(!isset($_SESSION['tmp']['devis_postes_' . $_POST['num_projet']]) || !is_array($_SESSION['tmp']['devis_postes_' . $_POST['num_projet']]))
            $_SESSION['tmp']['devis_postes_' . $_POST['num_projet']] = array();

        // Check if a corresponding line already exists (same poste and same salarié)
        $edited = false;
        foreach($_SESSION['tmp']['devis_postes_' . $_POST['num_projet']] as $id_ligne => $ligne_projet)
        {
            // If it is the case
            if($ligne_projet['id_poste'] == $_POST['poste'] && $ligne_projet['id_salarie'] == $_POST['salarie'])
            {
                // Just update the nb_heures
                $_SESSION['tmp']['devis_postes_' . $_POST['num_projet']][$id_ligne]['nb_heures'] += $_POST['nb_heures'];

                $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le poste a été mis à jour avec succès.');

                $edited = true;
            }
        }

        // If it is a new line
        if($edited == false)
        {
            // Store 
            $_SESSION['tmp']['devis_postes_' . $_POST['num_projet']][] = array
            (
                'id_poste' => $_POST['poste'],
                'id_salarie' => $_POST['salarie'],
                'nb_heures' => $_POST['nb_heures']
            );
        
            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le poste a été ajouté avec succès.');
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