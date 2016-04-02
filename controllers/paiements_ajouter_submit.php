<?php
    // Replace commas with points (french -> english notation)
    if(isset($_POST['quantite']))
        $_POST['quantite'] = str_replace(',', '.', $_POST['quantite']);

    // Check if all the required data are passed (correctly)
    if(!isset($_POST['devis'], $_POST['date_paiement'], $_POST['quantite']) || !is_numeric($_POST['devis']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du formulaire.');

    // Then check if all the required data aren't empty
    elseif(empty($_POST['devis']) || empty($_POST['date_paiement']) || empty($_POST['quantite']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Tous les champs ne sont pas rempli.');
    
    // And finally, check if dates are correct
    elseif(!is_numeric($_POST['quantite']) || $_POST['quantite'] <= 0.0)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La quantité payée est invalide.');

    // And finally, check if dates are correct
    elseif(strtotime($_POST['date_paiement']) === false)
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'La date de paiement est invalide.');

    // If everything's good
    else
    {
        // Firstly, clean $_POST
        $_POST = clean_post($_POST);

        // First, check if the devis exists and get the id_societe from the num_devis
        $query_check_devis = $slim->pdo->prepare('SELECT TP.id_societe
                                                    FROM ' . $config['db_prefix'] . 'T_Devis as TD
                                                    INNER JOIN ' . $config['db_prefix'] . 'T_Projet AS TP
                                                    ON TD.num_projet = TP.num_projet
                                                    WHERE TD.num_devis = :nd
                                                    AND TD.est_accepte = 1');

        $query_check_devis->bindParam(':nd', $_POST['devis']);
        $query_check_devis->execute();

        if($query_check_devis->rowCount() < 1)
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le devis associé est invalide.');
        else
        {
            $query_insert_devis_societe = $slim->pdo->prepare('INSERT INTO TJ_Devis_Societe (num_devis, id_societe, date_paiement, quantite_payee) VALUES (:nd, :is, :dp, :qp)');

            $query_insert_devis_societe->bindParam(':nd', $_POST['devis']);
            $query_insert_devis_societe->bindParam(':is', $query_check_devis->fetch()['id_societe']);
            $query_insert_devis_societe->bindParam(':dp', date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_paiement'])))); // We replace « / » by « - » to fix french dates notation
            $query_insert_devis_societe->bindParam(':qp', $_POST['quantite']);

            $query_insert_devis_societe->execute();

            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le paiement a été ajouté avec succès.');
        }
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

    header('Location: paiements_ajouter');
?>