<?php
    // Replace commas with points (french -> english notation)
    if(isset($_POST['tarif']))
        $_POST['tarif'] = str_replace(',', '.', $_POST['tarif']);
    
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['libelle'], $_POST['tarif'], $_POST['num_service']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['libelle']) || empty($_POST['tarif']) || empty($_POST['num_service']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the tarif is a number
    elseif(!is_numeric($_POST['tarif']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le tarif mensuel doit être un nombre');
    
    // And finally, check if the tarif is > 0
    elseif($_POST['tarif'] <= 0)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le tarif mensuel est trop bas.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);

        // Then we insert the service
        $query_insert_t_service = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Service SET libelle = :lib, tarif_mensuel = :th WHERE num_service = :np');

        // Again, bind the POST data to the prepare() variables
        $query_insert_t_service->bindParam(':lib', $_POST['libelle']);
        $query_insert_t_service->bindParam(':th', $_POST['tarif']);
        $query_insert_t_service->bindParam(':np', $_POST['num_service']);

        // Then execute the query
        $query_insert_t_service->execute();

        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le service a été édité avec succès.');
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

    header('Location: services_editer?id=' . $_POST['num_service']);
?>