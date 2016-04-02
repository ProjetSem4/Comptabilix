<?php
    // Check if all the required data are passed
    if(!isset($_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['tel'], $_POST['email']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['adresse']) || empty($_POST['cp']) && empty($_POST['ville']) || empty($_POST['tel']) || empty($_POST['email']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the zip code is numeric
    elseif(!is_numeric($_POST['cp']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le code postal n\'est pas un nombre.');

    // Same for the phone number
    elseif(!is_numeric($_POST['tel']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le numéro de téléphone n\'est pas un nombre.');

    // And finally check the e-mail address
    elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'e-mail rentré n\'est pas valide.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST from XSS attacks
        $_POST = clean_post($_POST);

        // Check if the person is already in the database
        $t_personne_id = get_personne_id($_POST);

        // If no result can be found
        if($t_personne_id === false)
        {
            // Then we insert the user
            $query_insert_t_personne = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'T_Personne (adresse, code_postal, ville, telephone, mail) 
                                                            VALUES (:adresse, :cp, :ville, :tel, :email)');

            // Again, bind the POST data to the prepare() variables
            $query_insert_t_personne->bindParam(':adresse', $_POST['adresse']);
            $query_insert_t_personne->bindParam(':cp', $_POST['cp']);
            $query_insert_t_personne->bindParam(':ville', $_POST['ville']);
            $query_insert_t_personne->bindParam(':tel', $_POST['tel']);
            $query_insert_t_personne->bindParam(':email', $_POST['email']);

            // Then execute the query
            $query_insert_t_personne->execute();

            // And finally get the personne_id
            $t_personne_id = get_personne_id($_POST);
        }

        // If nobody can be found, that's an error
        if($t_personne_id === false)         
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Whoops! Something went wrong while inserting data into the database...');   
        else
        {
            // Then insert the nom and the prénom into the database (if no exists)
            $query_select_personne_physique = $slim->pdo->query('SELECT id_personne FROM ' . $config['db_prefix'] . 'T_Personne_Physique WHERE id_personne = ' . $slim->pdo->quote($t_personne_id));

            // If none is found
            if($query_select_personne_physique->rowCount() < 1)
            {
                $query_insert_personne_physique = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'T_Personne_Physique VALUES (:id, :nom, :prenom)');
                
                $query_insert_personne_physique->bindParam(':id', $t_personne_id);
                $query_insert_personne_physique->bindParam(':nom', $_POST['nom']);
                $query_insert_personne_physique->bindParam(':prenom', $_POST['prenom']);

                $query_insert_personne_physique->execute();
            }

            // And then insert the id_personne into ' . $config['db_prefix'] . 'T_Salarie
            $query_insert_salarie = $slim->pdo->prepare('INSERT INTO ' . $config['db_prefix'] . 'T_Salarie VALUES (:id)');
            $query_insert_salarie->bindParam(':id', $t_personne_id);
            $query_insert_salarie->execute();

            // And finally go back to the right page
            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le salarié a été ajouté avec succès.');
        }
    }

    // Function used for getting the personne id matching the data in $post_data
    function get_personne_id($post_data)
    {
        global $slim;

        // First, check if a matching « personne » is already in the database
        $query_match = $slim->pdo->prepare('SELECT id_personne
            FROM ' . $config['db_prefix'] . 'T_Personne
            WHERE
                adresse = :adresse AND
                code_postal = :cp AND
                ville = :ville AND
                telephone = :tel AND
                mail = :email');

        // Then bind the POST data to the prepare() variables
        $query_match->bindParam(':adresse', $post_data['adresse']);
        $query_match->bindParam(':cp', $post_data['cp']);
        $query_match->bindParam(':ville', $post_data['ville']);
        $query_match->bindParam(':tel', $post_data['tel']);
        $query_match->bindParam(':email', $post_data['email']);

        // Then execute the query
        $query_match->execute();

        // If none is found, return false
        if($query_match->rowCount() < 1)
            return false;

        // Otherwise, return the id_personne
        $data = $query_match->fetch();

        return $data['id_personne'];
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    header('Location: salaries_ajouter');
?>