<?php
    // Check if all the required data are passed (correctly)
    if(!isset($_GET['id']) || !is_numeric($_GET['id']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du lien.');

    // If everything's good
    else
    {
        // Start by checking if there is already a quotation accepted for this project
        $query_check_devis = $slim->pdo->prepare('SELECT count(TD2.num_devis) as devis_acceptes
                                                    FROM ' . $config['db_prefix'] . 'T_Devis as TD1
                                                    INNER JOIN ' . $config['db_prefix'] . 'T_Devis as TD2
                                                    ON TD1.num_projet = TD2.num_projet
                                                    WHERE TD1.num_devis=:nd AND TD2.num_devis<>:nd AND TD2.est_accepte=1');
        $query_check_devis->bindParam(':nd', $_GET['id']);
        $query_check_devis->execute();
        
        // If none has been found
        if($query_check_devis->fetch()['devis_acceptes'] == 0)
        {
            $query_update_devis = $slim->pdo->prepare('UPDATE ' . $config['db_prefix'] . 'T_Devis SET est_accepte = 1, date_acceptation = :da WHERE num_devis = :nd');
            $query_update_devis->bindParam(':da', date('Y-m-d'));
            $query_update_devis->bindParam(':nd', $_GET['id']);
            $query_update_devis->execute();

            $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Le devis a été validé avec succès.');
        }
        else
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Le projet comporte déjà un devis validé.');
    }

    header('Location: devis_visualiser?id=' . $_GET['id']);
?>