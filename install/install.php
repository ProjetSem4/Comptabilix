<?php
    session_start();

    // Check if all the required data are passed
    if(!isset($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name'], $_POST['db_prefix'], $_POST['association_name'], $_POST['currency'], $_POST['url'], $_POST['prenom'], $_POST['nom'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['tel'], $_POST['email'], $_POST['motdepasse']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif
    (
        empty($_POST['db_host']) ||
        empty($_POST['db_name']) ||
        empty($_POST['association_name']) ||
        empty($_POST['currency']) ||
        empty($_POST['url']) ||
        empty($_POST['prenom']) ||
        empty($_POST['nom']) ||
        empty($_POST['adresse']) ||
        empty($_POST['cp']) ||
        empty($_POST['ville']) ||
        empty($_POST['tel']) ||
        empty($_POST['email']) ||
        empty($_POST['motdepasse'])
    )
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');

    // Then check if the zip code is numeric
    elseif(!is_numeric($_POST['cp']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le code postal n\'est pas un nombre.');

    // Same for the phone number
    elseif(!is_numeric($_POST['tel']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le numéro de téléphone n\'est pas un nombre.');

    // And finally check the e-mail address
    elseif(!filter_var($_POST['url'], FILTER_VALIDATE_URL))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'URL rentrée n\'est pas valide.');

    // And finally check the e-mail address
    elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'e-mail rentré n\'est pas valide.');

    // If everything's good
    else
    {
        // Before installation, sanitize $_POST
        $_POST = clean_post($_POST);

        // Start by checking if we can connect to the database
        $can_connect = false;

        try
        {
            $sql_conn = new PDO
            (
                'mysql:host=' . $_POST['db_host'] . ';dbname=' . $_POST['db_name'],
                $_POST['db_user'],
                $_POST['db_pass'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );

            $can_connect = true;
        }
        catch (PDOException $e)
        {
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Impossible de se connecter à la base de données.');
        }

        // Then execute all the install queries to create the database
        if($can_connect)
        {
            // Load the queries
            $install_queries = file_get_contents('Fortitudo.sql');

            // Set the prefix
            $install_queries = str_replace('{{DB_PREFIX}}', $_POST['db_prefix'], $install_queries);

            // And try to execute the query
            $database_created = false;
            try
            {
                $sql_conn->query($install_queries);
                $database_created = true;
            }
            catch (PDOException $e)
            {
                $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Impossible de créer la base de données.');
            }

            if($database_created)
            {
                // Then create a new access
                $query_insert_t_personne = $sql_conn->prepare('INSERT INTO ' . $_POST['db_prefix'] . 'T_Personne (adresse, code_postal, ville, telephone, mail) 
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
                $query_select_personne_physique = $sql_conn->query('SELECT id_personne FROM ' . $_POST['db_prefix'] . 'T_Personne_Physique WHERE id_personne = ' . $sql_conn->quote($t_personne_id));

                // If none is found
                if($query_select_personne_physique->rowCount() < 1)
                {
                    $query_insert_personne_physique = $sql_conn->prepare('INSERT INTO ' . $_POST['db_prefix'] . 'T_Personne_Physique VALUES (:id, :nom, :prenom)');
                    
                    $query_insert_personne_physique->bindParam(':id', $t_personne_id);
                    $query_insert_personne_physique->bindParam(':nom', $_POST['nom']);
                    $query_insert_personne_physique->bindParam(':prenom', $_POST['prenom']);

                    $query_insert_personne_physique->execute();
                }

                // And then insert the id_personne into ' . $_POST['db_prefix'] . 'T_membre
                $query_insert_membre = $sql_conn->prepare('INSERT INTO ' . $_POST['db_prefix'] . 'T_Membre VALUES (:id, 1)');
                $query_insert_membre->bindParam(':id', $t_personne_id);
                $query_insert_membre->execute();

                // Same for the password

                // Salt the new password
                $password = crypt($_POST['motdepasse'], $_POST['email']); // We salt the password with the login

                // Then insert it to the database
                $query_insert_identifiant = $sql_conn->prepare('INSERT INTO ' . $_POST['db_prefix'] . 'T_Identifiant (mot_de_passe, id_membre) VALUES(:mdp, :idm)');

                $query_insert_identifiant->bindParam(':mdp', $password);
                $query_insert_identifiant->bindParam(':idm', $t_personne_id);

                $query_insert_identifiant->execute();

                // Now write the config.php file

                // Read the sample file
                $config_file = file_get_contents('../config.sample.php');

                // Replace variables with their values
                $config_file = str_replace('[DB_HOST]', $_POST['db_host'], $config_file);
                $config_file = str_replace('[DB_USER]', $_POST['db_user'], $config_file);
                $config_file = str_replace('[DB_PASS]', $_POST['db_pass'], $config_file);
                $config_file = str_replace('[DB_NAME]', $_POST['db_name'], $config_file);
                $config_file = str_replace('[DB_PREFIX]', $_POST['db_prefix'], $config_file);
                $config_file = str_replace('[ASSOCIATION]', $_POST['association_name'], $config_file);
                $config_file = str_replace('[CURRENCY]', $_POST['currency'], $config_file);
                $config_file = str_replace('[URL]', $_POST['url'], $config_file);

                // And write it to the config file
                file_put_contents('../config.php', $config_file);

                // And finally delete the install/ folder
                rmdir_recursive('../install/');

                // Message to say that everything's fine
                $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'L\'installation s\'est déroulée avec succès. Vous pouvez maintenant vous connecter.');
            }
        }
    }

    // Function used for removing potential XSS attacks into $_POST
    function clean_post($post_data)
    {
        $cleaned_post_data = array();

        foreach($post_data as $key => $value)
            $cleaned_post_data[$key] = htmlspecialchars($value);

        return $cleaned_post_data;
    }

    // Function used for getting the personne id matching the data in $post_data
    function get_personne_id($post_data)
    {
        global $sql_conn;

        // First, check if a matching « personne » is already in the database
        $query_match = $sql_conn->prepare('SELECT id_personne
            FROM ' . $_POST['db_prefix'] . 'T_Personne
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

    // Function used to delete the install/ folder
    // Thanks to http://stackoverflow.com/a/7288067/5664392
    function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir($dir . '/' . $file)) rmdir_recursive($dir . '/' . $file);
            else unlink($dir . '/' . $file);
        }
        rmdir($dir);
    }

    header('Location: ../');
?>