<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['id']))
        die('Bad usage. $_GET[id] should be a number!');
    else
        $_GET['id'] = $slim->pdo->quote($_GET['id']);

    // Query the database
    $query = $slim->pdo->query('SELECT * FROM ' . $config['db_prefix'] . 'T_Projet WHERE num_projet = ' . $_GET['id']);

    // Check if the id is valid
    if($query->rowCount() < 1)
        die('Nothing found');

    $line = $query->fetch();

    // Get all the MOE
    $query_moe = $slim->pdo->query('SELECT id_membre FROM TJ_Membre_Projet WHERE num_projet = ' . $_GET['id']);
    $lines_moe = $query_moe->fetchAll();

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

    $templacat->set_variable("page_title", "Éditer " . $line['titre_projet']);
?>
<div class="panel panel-default contenu-page">
    <p><a href="projet_voir?id=<?php echo $line['num_projet']; ?>">« Retourner sur la fiche projet</a></p>
    <h1>Éditer la fiche de <?php echo $line['titre_projet']; ?></h1>
                    
    <form class="" role="form" method="post" action="projet_editer_submit">        
        <div class="form-group col-sm-12">
            <label for="titre">Titre du projet :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
                <input id="titre" name="titre" type="text" class="form-control" placeholder="Titre du projet" value="<?php echo $line['titre_projet']; ?>" required>
            </div>
        </div>

        <div class="form-group col-sm-6">
            <label for="id_client">Entreprise cliente :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                <select class="form-control" id="id_client" name="id_client" onchange="filter_moa(this.value)" required>
                    <?php
                        $query_list_societes = $slim->pdo->query('SELECT id_personne, raison_sociale FROM ' . $config['db_prefix'] . 'V_Societe ORDER BY raison_sociale ASC');
                        
                        while($line_societe = $query_list_societes->fetch())
                        {
                            echo '<option value="' . $line_societe['id_personne'] . '"';

                            if($line['id_societe'] == $line_societe['id_personne'])
                                echo ' selected';

                            echo '>' . $line_societe['raison_sociale'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>        

        <div class="form-group col-sm-6">
            <label for="id_moa">Maitrîse d'ouvrage :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                <select class="form-control" id="id_moa" name="id_moa" required>
                    <?php
                        $query_list_moa = $slim->pdo->query('SELECT id_personne, nom, prenom, id_societe
                                                            FROM ' . $config['db_prefix'] . 'V_MOA as VM
                                                            INNER JOIN ' . $config['db_prefix'] . 'TJ_Societe_MOA as TSM
                                                            ON VM.id_personne = TSM.id_MOA
                                                            ORDER BY prenom, nom ASC');

                        while($line_moa = $query_list_moa->fetch())
                        {
                            echo '<option value="' . $line_moa['id_personne'] . '" class="client_' . $line_moa['id_societe'] . '"';

                            if($line['id_MOA'] == $line_moa['id_personne'])
                                echo ' selected';

                            echo '>' . $line_moa['prenom'] . ' ' . $line_moa['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <label for="id_moe">Maitrîses d'œuvre :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                <select class="form-control" id="id_moe" name="id_moe[]" required multiple>
                    <?php
                        $query_list_moe = $slim->pdo->query('SELECT id_personne, nom, prenom FROM ' . $config['db_prefix'] . 'V_Membre ORDER BY prenom, nom ASC');
                        
                        while($line_moe = $query_list_moe->fetch())
                        {
                            echo '<option value="' . $line_moe['id_personne'] . '"';

                            for($i = 0; $i < count($lines_moe); $i++)
                            {
                                if($lines_moe[$i]['id_membre'] == $line_moe['id_personne'])
                                    echo ' selected';
                            }
                            
                            echo '>' . $line_moe['prenom'] . ' ' . $line_moe['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <input type="hidden" name="num_projet" value="<?php echo $line['num_projet']; ?>" />

        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Éditer le projet</button>
    </form>
</div>
<script src="template/filter_moa.js"></script>