<?php
    // Replace commas with points (french -> english notation)
    if(isset($_POST['tarif']))
        $_POST['tarif'] = str_replace(',', '.', $_POST['tarif']);

    if(isset($_POST['part']))
        $_POST['part'] = str_replace(',', '.', $_POST['part']);
    
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['libelle'], $_POST['tarif'], $_POST['num_poste'], $_POST['part']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['libelle']) || empty($_POST['tarif']) || empty($_POST['num_poste']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the tarif is a number
    elseif(!is_numeric($_POST['tarif']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le tarif horaire doit être un nombre');
    
    // And finally, check if the tarif is > 0
    elseif($_POST['tarif'] <= 0)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le tarif horaire est trop bas.');

    // Then check if the part_salariale is a number
    elseif(!is_numeric($_POST['part']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La part salariale horaire doit être un nombre');
    
    // Check if the part_salariale is > 0
    elseif($_POST['part'] <= 0)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La part salariale est trop bas.');

    // And check if the part_salariale is < tarif
    elseif($_POST['part'] > $_POST['tarif'])
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La part salariale est supérieure au tarif horaire.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);

        // Then we insert the poste
        $query_insert_t_poste = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Poste SET libelle = :lib, tarif_horaire = :th, part_salariale = :ps WHERE num_poste = :np');

        // Again, bind the POST data to the prepare() variables
        $query_insert_t_poste->bindParam(':lib', $_POST['libelle']);
        $query_insert_t_poste->bindParam(':th', $_POST['tarif']);
        $query_insert_t_poste->bindParam(':np', $_POST['num_poste']);
        $query_insert_t_poste->bindParam(':ps', $_POST['part']);

        // Then execute the query
        $query_insert_t_poste->execute();

        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le poste a été édité avec succès.');
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

    header('Location: postes_editer?id=' . $_POST['num_poste']);
?>