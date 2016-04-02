<?php
    // Check if all the required data are passed
    if(!isset($_POST['client_id'], $_POST['rs'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['tel'], $_POST['email']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['rs']) || empty($_POST['adresse']) || empty($_POST['cp']) && empty($_POST['ville']) || empty($_POST['tel']) || empty($_POST['email']) || empty($_POST['client_id']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the client_id is numeric
    elseif(!is_numeric($_POST['client_id']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'identifiant du client n\'est pas un nombre.');

    // Check if the zip code is numeric
    elseif(!is_numeric($_POST['cp']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le code postal n\'est pas un nombre.');

    // Same for the phone number
    elseif(!is_numeric($_POST['tel']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le numéro de téléphone n\'est pas un nombre.');

    // And finally check the e-mail address
    elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'e-mail rentré n\'est pas valide');

    // If everything's good
    else
    {
        // Firstly, clean the $_POST data
        $_POST = clean_post($_POST);

        // Then update the t_personne informations
        $query_update_t_personne = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Personne
            SET adresse = :adresse,
            code_postal = :cp,
            ville = :ville,
            telephone = :tel,
            mail = :email
            WHERE id_personne = :idp');

        // Again, bind the POST data to the prepare() variables
        $query_update_t_personne->bindParam(':idp', $_POST['client_id']);
        $query_update_t_personne->bindParam(':adresse', $_POST['adresse']);
        $query_update_t_personne->bindParam(':cp', $_POST['cp']);
        $query_update_t_personne->bindParam(':ville', $_POST['ville']);
        $query_update_t_personne->bindParam(':tel', $_POST['tel']);
        $query_update_t_personne->bindParam(':email', $_POST['email']);

        // Then execute the query
        $query_update_t_personne->execute();

        // Then update the raison_sociale into the database
        $query_update_societe = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Societe SET raison_sociale = :rs WHERE id_personne = :idp');
        
        $query_update_societe->bindParam(':idp', $_POST['client_id']);
        $query_update_societe->bindParam(':rs', $_POST['rs']);

        $query_update_societe->execute();

        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le client a été modifié avec succès.');
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    header('Location: clients_editer?id=' . $_POST['client_id']);
?>