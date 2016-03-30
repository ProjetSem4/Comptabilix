<?php
    // Check if all the required data are passed
    if(!isset($_POST['mail']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['mail']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // And finally check the e-mail address
    elseif(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'e-mail rentré n\'est pas valide.');

    // If everything's good
    else
    {
        // Check if the mail is in the database
        $query_validation_mail = $slim->pdo->prepare('SELECT id_personne FROM V_Identifiant WHERE mail = :mail');
        $query_validation_mail->bindParam(':mail', $_POST['mail']);
        $query_validation_mail->execute();

        // If there is no account
        if($query_validation_mail->rowCount() < 1 )
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'e-mail rentré n\'est associé à aucun compte.');
        
        // Or if there's one
        else
        {
            // Get the id_membre
            $id_membre = $query_validation_mail->fetch()['id_personne'];

            // Generate a recovery key
            $recovery_key = uniqid(rand(), true); // Random, unique
            $recovery_key = md5($recovery_key); // Unpredictable (mostly...)
            $recovery_key = substr($recovery_key, 0, mt_rand(15, 23)); // And between 15 and 23 characters

            // Save it to the database
            $query_set_recovery_key = $slim->pdo->prepare('UPDATE T_Identifiant SET cle_recuperation = :key WHERE id_membre = :idm');
            $query_set_recovery_key->bindParam(':key', $recovery_key);
            $query_set_recovery_key->bindParam(':idm', $id_membre);
            $query_set_recovery_key->execute();

            // And send an e-mail with the recovery link
            mail($_POST['mail'], 'Récupération de votre mot de passe', "Bonjour,\nvous (ou quelqu'un d'autre) a demandé la récupération de votre mot de passe Fortitudo pour l'association " . $config['association'] . ".\nPour le changer, rendez-vous sur " . $config['site_url'] . "connexion_changer_mdp?cle=" . $recovery_key . "\n\nCordialement,\nl'équipe Fortitudo.");
            //die($recovery_key);

            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Un e-mail contenant un lien de récupération a été envoyé à l\'adresse e-mail indiquée.');
        }
    }

    header('Location: connexion_mdp_oublie');
?>