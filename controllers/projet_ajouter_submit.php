<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['titre'], $_POST['id_client'], $_POST['id_moa']) || !is_numeric($_POST['id_client']) || !is_numeric($_POST['id_moa']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['titre']) || empty($_POST['id_client']) || empty($_POST['id_moa']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

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
        
        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le projet a été ajouté avec succès.');
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    header('Location: projet_ajouter');
?>