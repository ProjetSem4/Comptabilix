<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['titre'], $_POST['id_client'], $_POST['id_moa'], $_POST['num_projet']) || !is_numeric($_POST['id_client']) || !is_numeric($_POST['id_moa']) || !is_numeric($_POST['num_projet']) )
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['titre']) || empty($_POST['id_client']) || empty($_POST['id_moa']) || empty($_POST['num_projet']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);

        // Then we insert the projet
        $query_update_t_projet = $slim->pdo->prepare('UPDATE T_Projet
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
        
        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le projet a été édité avec succès.');
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    header('Location: projet_editer?id=' . $_POST['num_projet']);
?>