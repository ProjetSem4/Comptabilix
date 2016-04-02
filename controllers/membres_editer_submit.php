<?php
    // Check if all the required data are passed
    if(!isset($_POST['id_membre'], $_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['tel'], $_POST['email'], $_POST['actif'], $_POST['access']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['id_membre']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['adresse']) || empty($_POST['cp']) && empty($_POST['ville']) || empty($_POST['tel']) || empty($_POST['email']) || !is_numeric($_POST['actif']) || !is_numeric($_POST['access']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the zip code is numeric
    elseif(!is_numeric($_POST['cp']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le code postal n\'est pas un nombre.');

    // Same for the phone number
    elseif(!is_numeric($_POST['tel']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le numéro de téléphone n\'est pas un nombre.');

    // ... for the active parameter
    elseif($_POST['actif'] != 0 && $_POST['actif'] != 1)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le champ « est actif » est mal utilisé.');
    
    // ... and for the access parameter
    elseif($_POST['access'] != 0 && $_POST['access'] != 1)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le champ « accès à fortitudo » est mal utilisé.');

    // And finally check the e-mail address
    elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'e-mail rentré n\'est pas valide.');

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
        $query_update_t_personne->bindParam(':idp', $_POST['id_membre']);
        $query_update_t_personne->bindParam(':adresse', $_POST['adresse']);
        $query_update_t_personne->bindParam(':cp', $_POST['cp']);
        $query_update_t_personne->bindParam(':ville', $_POST['ville']);
        $query_update_t_personne->bindParam(':tel', $_POST['tel']);
        $query_update_t_personne->bindParam(':email', $_POST['email']);

        // Then execute the query
        $query_update_t_personne->execute();

        // Then update the first and last names into the database
        $query_update_personne_physique = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_personne_physique SET nom = :nom, prenom = :prenom WHERE id_personne = :idp');
        
        $query_update_personne_physique->bindParam(':idp', $_POST['id_membre']);
        $query_update_personne_physique->bindParam(':prenom', $_POST['prenom']);
        $query_update_personne_physique->bindParam(':nom', $_POST['nom']);

        $query_update_personne_physique->execute();

        // Same for the « actif » boolean
        $query_update_membre = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Membre SET actif = :actif WHERE id_personne = :idp');
        
        $query_update_membre->bindParam(':idp', $_POST['id_membre']);
        $query_update_membre->bindParam(':actif', $_POST['actif']);

        $query_update_membre->execute();

        // And same for the password

        // If we don't want the user to have an access to fortitudo, we just delete the row
        if($_POST['access'] == 0)
            $slim->pdo->query('DELETE FROM ' . $config['db_prefix'] . 'T_Identifiant WHERE id_membre = ' . $slim->pdo->quote($_POST['id_membre']));

        // Otherwise
        else
        {
            // Check if the user already have a password
            $query_select_identifiant = $slim->pdo->prepare('SELECT num_identifiant FROM ' . $config['db_prefix'] . 'T_Identifiant WHERE id_membre = :idm');
            $query_select_identifiant->bindParam(':idm', $_POST['id_membre']);
            $query_select_identifiant->execute();

            // If he doesn't
            if($query_select_identifiant->rowCount() < 1)
            {
                // Then add a new row
                $query_insert_identifiant = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'T_Identifiant (mot_de_passe, cle_recuperation, id_membre) VALUES("", :cle, :idm)');

                $query_insert_identifiant->bindParam(':idm', $_POST['id_membre']);

                // We generate a recovery key, so the user will be able to create its own password
                $recovery_key = uniqid(rand(), true); // Random, unique
                $recovery_key = md5($recovery_key); // Unpredictable (mostly...)
                $recovery_key = substr($recovery_key, 0, mt_rand(15, 23)); // And between 15 and 23 characters

                // Add it to the line
                $query_insert_identifiant->bindParam(':cle', $recovery_key);

                $query_insert_identifiant->execute();

                // And then send a link to the user
                mail($_POST['mail'], 'Accès à Fortitudo', "Bonjour,\nvous (ou quelqu'un d'autre) vous a autorisé l'accès au logiciel de comptabilité Fortitudo pour l'association " . $config['association'] . ".\nPour définir votre mot de passe, rendez-vous sur " . $config['site_url'] . "connexion_changer_mdp?cle=" . $recovery_key . "\n\nCordialement,\nl'équipe Fortitudo.");
            }
        }
        
        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le client a été modifié avec succès.');
    }

    // Function used for getting the personne id matching the data in $post_data
    function get_personne_id($post_data)
    {
        global $slim;

        // First, check if a matching « personne » is already in the database
        $query_match = $slim->pdo->prepare('SELECT id_personne
            FROM ' . $config['db_prefix'] . 'T_Personne
            WHERE
                adresse = :adresse AND
                code_postal = :cp AND
                ville = :ville AND
                telephone = :tel AND
                mail = :email');

        // Then bind the POST data to the prepare() variables
        $query_match->bindParam(':adresse', $post_data['adresse']);
        $query_match->bindParam(':cp', $post_data['cp']);
        $query_match->bindParam(':ville', $post_data['ville']);
        $query_match->bindParam(':tel', $post_data['tel']);
        $query_match->bindParam(':email', $post_data['email']);

        // Then execute the query
        $query_match->execute();

        // If none is found, return false
        if($query_match->rowCount() < 1)
            return false;

        // Otherwise, return the id_personne
        $data = $query_match->fetch();

        return $data['id_personne'];
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    header('Location: membres_editer?id=' . $_POST['id_membre']);
?>