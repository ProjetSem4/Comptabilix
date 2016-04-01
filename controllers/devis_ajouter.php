<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['pid']))
        die('Bad usage. $_GET[pid] should be a number!');
    else
        $_GET['pid'] = $slim->pdo->quote($_GET['pid']);

    // Query the database
    $query = $slim->pdo->query('SELECT * FROM T_Projet WHERE num_projet = ' . $_GET['pid']);

    // Check if the id is valid
    if($query->rowCount() < 1)
        die('Nothing found');

    $line = $query->fetch();

    $templacat->set_variable('page_title', 'Créer un nouveau devis pour ' . $line['titre_projet']);

    // Show message(s), if needed
    if(isset($_SESSION['fortitudo_messages']) && is_array($_SESSION['fortitudo_messages']))
    {
        // For each message
        foreach($_SESSION['fortitudo_messages'] as $message)
        {
            // Use a different layout, determined by the type of the message
            switch($message['type'])
            {
                case 'error' : 
                    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message['content'] . '</div>';
                    break;
            
                case 'success' : 
                    echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message['content'] . '</div>';
                    break;
            }
        }

        // Clean the message queue
        $_SESSION['fortitudo_messages'] = array();
    }
?>    
<div class="panel panel-default contenu-page">
    <p><a href="projet_visualiser?id=<?php echo $line['num_projet']; ?>">« Retourner sur la fiche projet</a></p>
    <h1>Créer un nouveau devis pour <?php echo $line['titre_projet']; ?> <a class="btn btn-success pull-right" href="devis_ajouter_submit?pid=<?php echo $line['num_projet']; ?>"><span class="glyphicon glyphicon-ok"></span> Valider</a></h1>
                    
    <h3>Postes</h3>
    <table class="table">
        <?php
            // Check if postes has been added to the temporary session variable
            if(isset($_SESSION['tmp']['devis_postes_' . $line['num_projet']]) && is_array($_SESSION['tmp']['devis_postes_' . $line['num_projet']]) && count($_SESSION['tmp']['devis_postes_' . $line['num_projet']]) > 0)
            {
                echo '<tr><th>#</th><th>Nom du poste</th><th>Tarif horaire</th><th>Nombre d\'heures</th><th>Coût total</th><th>Salarié</th><th style="width: 50px">Action</th></tr>';

                foreach($_SESSION['tmp']['devis_postes_' . $line['num_projet']] as $id_ligne => $ligne_poste)
                {
                    $query_select_poste = $slim->pdo->query('SELECT * FROM T_Poste WHERE num_poste = ' . $slim->pdo->quote($ligne_poste['id_poste']));
                    $res_select_poste = $query_select_poste->fetch();

                    $query_select_salarie = $slim->pdo->query('SELECT * FROM V_Salarie WHERE id_personne = ' . $slim->pdo->quote($ligne_poste['id_salarie']));
                    $res_select_salarie = $query_select_salarie->fetch();

                    echo '<tr>
                            <td>' . $ligne_poste['id_poste'] . '</td>
                            <td>' . $res_select_poste['libelle'] . '</td>
                            <td>' . $res_select_poste['tarif_horaire'] . ' '  . $config['currency'] . '</td>
                            <td>' . $ligne_poste['nb_heures'] . '</td>
                            <td>' . $ligne_poste['nb_heures'] * $res_select_poste['tarif_horaire']. ' '  . $config['currency'] . '</td>
                            <td>' . $res_select_salarie['prenom'] . ' ' . $res_select_salarie['nom'] . '</td>
                            <td><a class="btn btn-danger" title="Supprimer" href="devis_supprimer_poste?pid=' . $line['num_projet'] . '&id=' . $id_ligne . '"><span class="glyphicon glyphicon-remove"></span></a>
                            </td>
                        </tr>';   
                }
            }
            else
                echo '<tr><td colspan="7" style="text-align:center">Aucun poste associé n\'a pu être trouvé</td></td>';
        ?>
    </table>

    <h3>Services</h3>
    <table class="table">
        <?php
            // Check if services has been added to the temporary session variable
            if(isset($_SESSION['tmp']['devis_services_' . $line['num_projet']]) && is_array($_SESSION['tmp']['devis_services_' . $line['num_projet']]) && count($_SESSION['tmp']['devis_services_' . $line['num_projet']]) > 0)
            {
                echo '<tr><th>#</th><th>Nom du service</th><th>Tarif mensuel</th><th>Date de début</th><th>Date de fin</th><th style="width: 50px">Action</th></tr>';

                foreach($_SESSION['tmp']['devis_services_' . $line['num_projet']] as $id_ligne => $ligne_service)
                {
                    $query_select_service = $slim->pdo->query('SELECT * FROM T_Service WHERE num_service = ' . $slim->pdo->quote($ligne_service['id_service']));
                    $res_select_service = $query_select_service->fetch();

                    echo '<tr>
                        <td>' . $res_select_service['num_service'] . '</td>
                        <td>' . $res_select_service['libelle'] . '</td>
                        <td>' . $res_select_service['tarif_mensuel'] . ' ' . $config['currency'] . '</td>
                        <td>' . date('d/m/Y', $ligne_service['date_debut']) . '</td>
                        <td>' . (($ligne_service['date_fin'] == '') ? 'n/a' : date('d/m/Y', $ligne_service['date_fin'])) . '</td>
                        <td><a class="btn btn-danger" title="Supprimer" href="devis_supprimer_service?pid=' . $line['num_projet'] . '&id=' . $id_ligne . '"><span class="glyphicon glyphicon-remove"></span></a>
                        </td>
                    </tr>';

                }
            }
            else
                echo '<tr><td colspan="4" style="text-align:center">Aucun service associé n\'a pu être trouvé</td></td>';
        ?>
    </table>

    <h3>Ajouter un poste</h3>
    <form role="form" method="post" action="devis_ajouter_poste_submit">        
        <div class="form-group col-sm-5">
            <label for="poste">Poste :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
                <select id="poste" name="poste" class="form-control" required>
                    <?php
                        $query_list_postes = $slim->pdo->query('SELECT * FROM T_Poste ORDER BY libelle ASC');
                        while($poste = $query_list_postes->fetch())
                        {
                            echo '<option value="' . $poste['num_poste'] . '">' . $poste['libelle'] . ' (' . $poste['tarif_horaire'] . ' ' . $config['currency'] . '/heure)</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-4">
            <label for="salarie">Salarié :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                <select id="salarie" name="salarie" class="form-control" required>
                    <?php
                        $query_list_salaries = $slim->pdo->query('SELECT id_personne, prenom, nom FROM V_Salarie ORDER BY prenom, nom ASC');
                        while($salarie = $query_list_salaries->fetch())
                        {
                            echo '<option value="' . $salarie['id_personne'] . '">' . $salarie['prenom'] . ' ' . $salarie['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-3">
            <label for="nb_heures">Nombre d'heures :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-time"></span></div>
                <input id="nb_heures" name="nb_heures" type="number" class="form-control" min="1" value="1" required>
            </div>
        </div>

        <input type="hidden" value="<?php echo $line['num_projet']; ?>" name="num_projet" />

        <button type="submit" class="btn btn-success">Ajouter le poste</button>
    </form>

    <h3>Ajouter un service</h3>
    <form role="form" method="post" action="devis_ajouter_service_submit">        
        <div class="form-group col-sm-6">
            <label for="service">Service :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
                <select id="service" name="service" class="form-control" required>
                    <?php
                        $query_list_services = $slim->pdo->query('SELECT * FROM T_Service ORDER BY libelle ASC');
                        while($service = $query_list_services->fetch())
                        {
                            echo '<option value="' . $service['num_service'] . '">' . $service['libelle'] . ' (' . $service['tarif_mensuel'] . ' ' . $config['currency'] . '/mois)</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-3">
            <label for="date_debut">Date de début :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                <input id="date_debut" name="date_debut" type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" placeholder="JJ/MM/AAAA" required>
            </div>
        </div>

        <div class="form-group col-sm-3">
            <label for="date_fin">Date de fin (si besoin) :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                <input id="date_fin" name="date_fin" type="text" class="form-control" placeholder="JJ/MM/AAAA" />
            </div>
        </div>
        
        <input type="hidden" value="<?php echo $line['num_projet']; ?>" name="num_projet" />
        
        <button type="submit" class="btn btn-success">Ajouter le service</button>
    </form>

</div>