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
        $query_insert_t_projet = $slim->pdo->prepare('INSERT INTO T_Projet (titre_projet, date_creation, id_moa, id_societe) 
                                                        VALUES (:titre_projet, :date_creation, :id_moa, :id_societe)');

        // Again, bind the POST data to the prepare() variables
        $query_insert_t_projet->bindParam(':titre_projet', $_POST['titre']);
        $query_insert_t_projet->bindParam(':id_moa', $_POST['id_moa']);
        $query_insert_t_projet->bindParam(':id_societe', $_POST['id_client']);
        $query_insert_t_projet->bindParam(':date_creation', date('Y-m-d'));

        // Then execute the query
        $query_insert_t_projet->execute();

        // Now get new num_projet of the newly inserted project
        $query_select_t_projet = $slim->pdo->prepare('SELECT num_projet
                                                        FROM T_Projet
                                                        WHERE 
                                                            titre_projet = :titre_projet AND
                                                            date_creation = :date_creation AND
                                                            id_moa = :id_moa AND 
                                                            id_societe = :id_societe
                                                        ORDER BY num_projet DESC');

        // Again, bind the POST data to the prepare() variables
        $query_select_t_projet->bindParam(':titre_projet', $_POST['titre']);
        $query_select_t_projet->bindParam(':id_moa', $_POST['id_moa']);
        $query_select_t_projet->bindParam(':id_societe', $_POST['id_client']);
        $query_select_t_projet->bindParam(':date_creation', date('Y-m-d'));

        $query_select_t_projet->execute();

        $num_projet = $query_select_t_projet->fetch()['num_projet'];

        // Do the same for the list of moe
        foreach($_POST['id_moe'] as $single_id_moe)
        {
            // For each id_moe, we insert it
            $query_insert_tj_mp = $slim->pdo->prepare('INSERT INTO TJ_Membre_Projet (id_membre, num_projet) VALUES (:id_moe, :num_projet)');
            
            $query_insert_tj_mp->bindParam(':id_moe', $single_id_moe);
            $query_insert_tj_mp->bindParam(':num_projet', $num_projet);

            $query_insert_tj_mp->execute();
        }
        
        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le projet a été ajouté avec succès.');
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

    header('Location: projet_ajouter');
?>