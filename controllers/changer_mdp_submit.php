<?php
    // Check if all the required data are passed
    if(!isset($_POST['password_a'], $_POST['password_n1'], $_POST['password_n2']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['password_a']) || empty($_POST['password_n1']) || empty($_POST['password_n2']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // And check if the two new passwords are the same
    elseif($_POST['password_n1'] !== $_POST['password_n2'])
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Les deux mots de passe ne correspondent pas.');

    // If everything's good
    else
    {
        // Check if the current password is the good one

        // Hash the password
        $password = crypt($_POST['password_a'], $_SESSION['connection_state']['mail']); // We salt the password with the login
        $password = $slim->pdo->quote($password);

        // Check if the user exists, if the password is right and if he has the right to connect
        $query = $slim->pdo->query('SELECT num_identifiant
            FROM T_Identifiant
            WHERE
                id_membre = ' . $slim->pdo->quote($_SESSION['connection_state']['id']) . ' AND 
                mot_de_passe = ' . $password);

         // If nothing was found
        if($query->rowCount() == 0)
        {
            // Show an error message
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le mot de passe actuel est incorrect.');
        }
        else
        {
            // Salt the new password
            $password2 = crypt($_POST['password_n1'], $_SESSION['connection_state']['mail']); // We salt the password with the login
            $password2 = $slim->pdo->quote($password2);

            // And update the database with the new password
            $num_identifiant = $slim->pdo->quote($query->fetch()['num_identifiant']);

            $slim->pdo->query('UPDATE T_Identifiant SET mot_de_passe = ' . $password2 . ' WHERE num_identifiant = ' . $num_identifiant);

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

    header('Location: mon_compte');
?>