<?php
    // Check is everything's good
    $insert_ok = false;

    // Check if all the required data are passed
    if(!isset($_POST['role'], $_POST['cid'], $_POST['id_moa']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['role']) || empty($_POST['cid']) || empty($_POST['id_moa']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the client id is numeric
    elseif(!is_numeric($_POST['cid']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'identifiant du client n\'est pas un nombre.');

    // Same for the id_moa
    elseif(!is_numeric($_POST['id_moa']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'identifiant du MOA n\'est pas un nombre.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);
        
        // Then we insert the relation
        $query_insert_tj_sm = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'TJ_Societe_MOA (id_societe, id_MOA, titre) 
                                                        VALUES (:ids, :idm, :titre)');

        // Again, bind the POST data to the prepare() variables
        $query_insert_tj_sm->bindParam(':ids', $_POST['cid']);
        $query_insert_tj_sm->bindParam(':idm', $_POST['id_moa']);
        $query_insert_tj_sm->bindParam(':titre', $_POST['role']);

        // Then execute the query
        $query_insert_tj_sm->execute();

        $insert_ok = true;
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    if($insert_ok)
        header('Location: clients_visualiser?id=' . $_POST['cid']);
    else
        header('Location: moa_ajouter_etape2?id_moa=' . $_POST['id_moa'] . '&cid=' . $_POST['cid']);
?>