<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['titre'], $_POST['id_client'], $_POST['id_moa']) || !is_numeric($_POST['id_client']) || !is_numeric($_POST['id_moa']) || !is_array($_POST['id_moe']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['titre']) || empty($_POST['id_client']) || empty($_POST['id_moa']) || empty($_POST['id_moe']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if there is at least one MOE
    elseif(count($_POST['id_moe']) < 1)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Il faut au moins une maîtrise d\'œuvre pour ce projet.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);

        // Then we insert the projet
        $query_update_t_projet = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Projet
            SET titre_projet = :titre_projet,
                id_moa = :id_moa,
                id_societe = :id_societe
            WHERE num_projet = :num_projet');

        // Again, bind the POST data to the prepare() variables
        $query_update_t_projet->bindParam(':titre_projet', $_POST['titre']);
        $query_update_t_projet->bindParam(':id_moa', $_POST['id_moa']);
        $query_update_t_projet->bindParam(':id_societe', $_POST['id_client']);
        $query_update_t_projet->bindParam(':num_projet', $_POST['num_projet']);

        // Then execute the query
        $query_update_t_projet->execute();

        // Now remove all the MOEs for the project
        $slim->pdo->query('DELETE FROM ' . $config['db_prefix'] . 'TJ_Membre_Projet WHERE num_projet = ' . $slim->pdo->quote($_POST['num_projet']));

        // And insert them back
        foreach($_POST['id_moe'] as $single_id_moe)
        {
            // For each id_moe, we insert it
            $query_insert_tj_mp = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'TJ_Membre_Projet (id_membre, num_projet) VALUES (:id_moe, :num_projet)');
            
            $query_insert_tj_mp->bindParam(':id_moe', $single_id_moe);
            $query_insert_tj_mp->bindParam(':num_projet', $_POST['num_projet']);

            $query_insert_tj_mp->execute();
        }
        
        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le projet a été édité avec succès.');
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

    header('Location: projet_editer?id=' . $_POST['num_projet']);
?>