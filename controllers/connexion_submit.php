<?php
    // Check if all the required data are passed
    if(!isset($_POST['login'], $_POST['password']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['login']) || empty($_POST['password']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // If everything's good
    else
    {
        // Protect the login against SQL injections
        $login = $slim->pdo->quote($_POST['login']);

        // And then hash the password
        $password = crypt($_POST['password'], $_POST['login']); // We salt the password with the login
        $password = $slim->pdo->quote($password);

        // Check if the user exists, if the password is right and if he has the right to connect
        $query = $slim->pdo->query('SELECT V_Identifiant.id_personne, nom, prenom, V_Identifiant.mail
            FROM V_Identifiant
            INNER JOIN V_Membre
            ON V_Identifiant.id_personne = V_Membre.id_personne
            WHERE
                V_Identifiant.mail = ' . $login . ' AND 
                mot_de_passe = ' . $password . ' AND
                V_Identifiant.actif = 1 AND
                cle_recuperation IS NULL');

        // If nothing was found
        if($query->rowCount() == 0)
        {
            // Show an error message
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais nom d\'utilisateur ou mot de passe. <a href="connexion_mdp_oublie">Demander un nouveau mot de passe</a>.');

            // And then go back to the last page
            header('Location: /connexion');
        }
        else
        {
            // Fetch the user informations
            $user_infos = $query->fetch();

            // The user is now connected
            $_SESSION['connection_state'] = array
                (
                    'connected' => true,
                    'name'      => $user_infos['prenom'] . ' ' . $user_infos['nom'],
                    'id'        => $user_infos['id_personne'],
                    'mail'      => $user_infos['mail']
                );

            // So he can go to the index
            header('Location: /');
        }
    }
?>