<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['id']))
        die('Bad usage. $_GET[id] should be a number!');
    else
        $_GET['id'] = $slim->pdo->quote($_GET['id']);

    // Query the database
    $query = $slim->pdo->query('SELECT V_Membre.*, V_Identifiant.id_personne as compte_actif FROM V_Membre
                                LEFT JOIN V_Identifiant ON V_Membre.id_personne = V_Identifiant.id_personne
                                WHERE V_Membre.id_personne = ' . $_GET['id']);

    // Check if the id is valid
    if($query->rowCount() < 1)
        die('Nothing found');

    $line = $query->fetch();

    $templacat->set_variable("page_title", "Éditer " . $line['prenom'] . ' ' . $line['nom']);

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
    <p><a href="membres_voir?id=<?php echo $line['id_personne']; ?>">« Retourner sur la fiche membre</a></p>
    <h1>Éditer la fiche de <?php echo $line['prenom'] . ' ' . $line['nom']; ?></h1>
    
    <form class="" role="form" method="post" action="membres_editer_submit">
        <h2>Informations sur le membre</h2>
        <div class="form-group col-sm-6">
            <label for="prenom">Prénom : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span></div>
                <input id="prenom" name="prenom" type="text" class="form-control"  placeholder="Prénom" value="<?php echo $line['prenom']; ?>" required>
            </div>
        </div>   

        <div class="form-group col-sm-6">
            <label for="nom">Nom : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span> </div>
                <input id="nom" name="nom" type="text" class="form-control" placeholder="Nom" value="<?php echo $line['nom']; ?>" required>
            </div>
        </div>

        <div class="form-group col-sm-8">
            <label for="titre">Titre dans l'association : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-king"> </span></div>
                <input id="titre" name="titre" type="text" class="form-control" placeholder="Titre dans l'association" required>
            </div>
        </div>
        <div class="form-group col-sm-4">
            <label for="actif">Est actif ?</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-thumbs-up"> </span></div>
                <select id="actif" name="actif" class="form-control" required>
                    <option value="1"<?php if($line['actif'] == 1) echo ' selected'; ?>>Oui</option>
                    <option value="0"<?php if($line['actif'] == 0) echo ' selected'; ?>>Non</option>
                </select>
            </div>
        </div>
   
        <div class="form-group col-sm-12">
            <label for="adresse">Adresse : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
                <input id="adresse" name="adresse" type="adress" class="form-control" placeholder="Adresse" value="<?php echo $line['adresse']; ?>">
            </div>
        </div>
        
        <div class="form-group col-sm-4">
            <label for="cp">Code postal :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
                <input id="cp" name="cp" type="text" class="form-control" placeholder="Code postal" value="<?php echo $line['code_postal']; ?>" required>
            </div>
        </div>

        <div class="form-group col-sm-8">
            <label for="ville">Ville :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
                <input id="ville" name="ville" type="text" class="form-control" placeholder="Ville" value="<?php echo $line['ville']; ?>" required>
            </div>
        </div>
   
        <div class="form-group col-sm-6">
            <label for="tel">Numéro de téléphone : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
                <input type="phone" name="tel" class="form-control" id="tel" placeholder="Téléphone" value="<?php echo $line['telephone']; ?>">
            </div>
        </div>
   
        <div class="form-group col-sm-6">
            <label for="email">Adresse e-mail : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" value="<?php echo $line['mail']; ?>">
            </div>
        </div>

        <h2>Accès à fortitudo</h2>

        <div class="form-group col-sm-12">
            <label for="access">Activer l'accès à Fortitudo ?</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></div>
                <select required name="access" id="access" class="form-control">
                    <option value="0"<?php if(is_null($line['compte_actif'])) echo ' selected'; ?>>Non</option>
                    <option value="1"<?php if(!is_null($line['compte_actif'])) echo ' selected'; ?>>Oui</option>
                </select>
            </div>
        </div>

        <input type="hidden" value="<?php echo $line['id_personne']; ?>" name="id_membre" />
        
        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Éditer le membre</button>  
    </form>
</div>                 