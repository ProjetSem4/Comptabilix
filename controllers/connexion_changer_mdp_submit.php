<?php
    // Check if all the required data are passed
    if(!isset($_POST['cle'], $_POST['password_n1'], $_POST['password_n2']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['cle']) || empty($_POST['password_n1']) || empty($_POST['password_n2']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // And check if the two new passwords are the same
    elseif($_POST['password_n1'] !== $_POST['password_n2'])
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Les deux mots de passe ne correspondent pas.');

    // If everything's good
    else
    {
        // Check if the key is valid

        // Check if the user exists, if the password is right and if he has the right to connect
        $query = $slim->pdo->query('SELECT num_identifiant, mail
            FROM ' . $config['db_prefix'] . 'T_Identifiant
            INNER JOIN ' . $config['db_prefix'] . 'V_Identifiant
            ON ' . $config['db_prefix'] . 'T_Identifiant.id_membre = ' . $config['db_prefix'] . 'V_Identifiant.id_personne
            WHERE
                ' . $config['db_prefix'] . 'T_Identifiant.cle_recuperation = ' . $slim->pdo->quote($_POST['cle']));

         // If nothing was found
        if($query->rowCount() == 0)
        {
            // Show an error message
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La clé est incorrecte.');
        }
        else
        {
            // Get the user informations
            $user = $query->fetch();

            // Salt the new password
            $password2 = crypt($_POST['password_n1'], $user['mail']); // We salt the password with the login
            $password2 = $slim->pdo->quote($password2);

            // And update the database with the new password
            $num_identifiant = $slim->pdo->quote($user['num_identifiant']);

            $slim->pdo->query('UPDATE ' . $config['db_prefix'] . 'T_Identifiant SET mot_de_passe = ' . $password2 . ', cle_recuperation = NULL WHERE num_identifiant = ' . $num_identifiant);

            // And finally go back to the right page
            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le mot de passe a été changé avec succès.');
        }
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    header('Location: connexion');
?>