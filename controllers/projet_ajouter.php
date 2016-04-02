<?php
    $templacat->set_variable("page_title", "Ajouter un projet");

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
    <p><a href="projet_voir">« Retourner à la liste des projets</a></p>
    <h1>Ajouter un projet</h1>
                    
    <form class="" role="form" method="post" action="projet_ajouter_submit">        
        <div class="form-group col-sm-12">
            <label for="titre">Titre du projet :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
                <input id="titre" name="titre" type="text" class="form-control" placeholder="Titre du projet" required>
            </div>
        </div>

        <div class="form-group col-sm-6">
            <label for="id_client">Entreprise cliente :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                <select class="form-control" id="id_client" name="id_client" onchange="filter_moa(this.value)" required>
                    <?php
                        $query_list_societes = $slim->pdo->query('SELECT id_personne, raison_sociale FROM ' . $config['db_prefix'] . 'V_Societe ORDER BY raison_sociale ASC');
                        
                        while($line = $query_list_societes->fetch())
                        {
                            echo '<option value="' . $line['id_personne'] . '">' . $line['raison_sociale'] . '</option>';
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
                        
                        while($line = $query_list_moa->fetch())
                        {
                            echo '<option value="' . $line['id_personne'] . '" class="client_' . $line['id_societe'] . '">' . $line['prenom'] . ' ' . $line['nom'] . '</option>';
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
                        
                        while($line = $query_list_moe->fetch())
                        {
                            echo '<option value="' . $line['id_personne'] . '">' . $line['prenom'] . ' ' . $line['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Ajouter le projet</button>
    </form>
</div>
<script src="template/filter_moa.js"></script>