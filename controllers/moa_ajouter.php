<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['cid']))
        die('Bad usage. $_GET[cid] should be a number!');
    else
        $_GET['cid'] = htmlspecialchars($_GET['cid']);

    $templacat->set_variable("page_title", "Ajouter une maîtrise d'ouvrage (étape 1/2)");

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
    <p><a href="clients_visualiser?id=<?php echo $_GET['cid']; ?>">« Retourner à la fiche client</a></p>
    <h1>Ajouter une maîtrise d'ouvrage (étape 1/2)</h1>
    
    <h2>Créer une nouvelle maîtrise d'ouvrage</h2>
    <form class="" role="form" method="post" action="moa_creer_submit">
        <div class="form-group col-sm-6">
            <label for="nom">Nom du MOA :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                <input id="nom" name="nom" type="text" class="form-control" placeholder="Nom" required>
            </div>
        </div>
                        
        <div class="form-group col-sm-6">
            <label for="prenom">Prénom du MOA :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                <input id="prenom" name="prenom" type="text" class="form-control" placeholder="Prénom" required>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="adresse">Adresse du MOA :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
                <input id="adresse" name="adresse" type="text" class="form-control" placeholder="Adresse" required>
            </div>
        </div>
        <div class="form-group col-sm-4">
            <label for="cp">Code postal :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
                <input id="cp" name="cp" type="text" class="form-control" placeholder="Code postal" required>
            </div>
        </div>
        <div class="form-group col-sm-8">
            <label for="email">Ville :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
                <input type="text" name="ville" class="form-control" placeholder="Ville" required>
            </div>
        </div>
                        
        <div class="form-group col-sm-6">
            <label for="email">Numéro de téléphone :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
                <input type="text" name="tel" class="form-control" placeholder="Téléphone" required>
            </div>
        </div>
        
        <div class="form-group col-sm-6">
            <label for="email">Adresse e-mail :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
        </div>

        <input type="hidden" name="cid" value="<?php echo $_GET['cid']; ?>" />
                        
        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Créer le MOA et passer à l'étape suivante</button>
    </form>
    
    <h3 class="text-center">OU</h3>

    <h2>Sélectionner une maîtrise d'ouvrage existante</h2>

    <form class="" role="form" method="get" action="moa_ajouter_etape2">
        <div class="form-group col-sm-12">
            <label for="nom">Nom du MOA :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                <select class="form-control" id="id_moa" name="id_moa" required>
                    <?php
                        $query_list_moa = $slim->pdo->query('SELECT id_personne, nom, prenom FROM ' . $config['db_prefix'] . 'V_MOA ORDER BY prenom, nom ASC');
                        
                        while($line = $query_list_moa->fetch())
                        {
                            echo '<option value="' . $line['id_personne'] . '">' . $line['prenom'] . ' ' . $line['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <input type="hidden" name="cid" value="<?php echo $_GET['cid']; ?>" />
                        
        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Sélectionner le MOA et passer à l'étape suivante</button>
    </form>
</div>