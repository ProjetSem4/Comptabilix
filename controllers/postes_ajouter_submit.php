<?php
    // Replace commas with points (french -> english notation)
    if(isset($_POST['tarif']))
        $_POST['tarif'] = str_replace(',', '.', $_POST['tarif']);
    
    // Check if all the required data are passed (correctly)
    if(!isset($_POST['libelle'], $_POST['tarif']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['libelle']) || empty($_POST['tarif']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the tarif is a number
    elseif(!is_numeric($_POST['tarif']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le tarif horaire doit être un nombre');
    
    // And finally, check if the tarif is > 0
    elseif($_POST['tarif'] <= 0)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le tarif horaire est trop bas.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);

        // Then we insert the poste
        $query_insert_t_poste = $slim->pdo->prepare('INSERT INTO T_Poste (libelle, tarif_horaire) VALUES (:lib, :th)');

        // Again, bind the POST data to the prepare() variables
        $query_insert_t_poste->bindParam(':lib', $_POST['libelle']);
        $query_insert_t_poste->bindParam(':th', $_POST['tarif']);

        // Then execute the query
        $query_insert_t_poste->execute();

        // And finally go back to the right page
        $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le poste a été ajouté avec succès.');
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

    header('Location: postes_ajouter');
?>