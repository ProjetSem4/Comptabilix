<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_SESSION['connection_state']['id']))
        die('Are you really connected?');
    else
        $id_personne = $slim->pdo->quote($_SESSION['connection_state']['id']);

    // Query the database
    $query = $slim->pdo->query('SELECT ' . $config['db_prefix'] . 'V_Membre.*, ' . $config['db_prefix'] . 'V_Identifiant.id_personne as compte_actif FROM ' . $config['db_prefix'] . 'V_Membre
                                LEFT JOIN ' . $config['db_prefix'] . 'V_Identifiant ON ' . $config['db_prefix'] . 'V_Membre.id_personne = ' . $config['db_prefix'] . 'V_Identifiant.id_personne
                                WHERE ' . $config['db_prefix'] . 'V_Membre.id_personne = ' . $id_personne);

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
    <h1>Mon compte</h1>
    
    <h2>Informations personnelles <a class="btn btn-warning pull-right" href="membres_editer?id=<?php echo $line['id_personne']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer</a></h2>
    <table class="table">
        <tr>
            <td class="titre-tableau">Nom</td>
            <td><span class="glyphicon glyphicon-user"></span> <?php echo $line['prenom'] . ' ' . $line['nom']; ?></td>
        </tr>

        <tr>
            <td class="titre-tableau">Adresse</td>
            <td><span class="glyphicon glyphicon-home"></span> <?php echo $line['adresse']; ?></td>
        </tr>
        <tr>
            <td class="titre-tableau">Ville</td>
            <td><span class="glyphicon glyphicon-map-marker"></span> <?php echo $line['code_postal'] . ' ' . $line['ville']; ?></td>
        </tr>
        <tr>
            <td class="titre-tableau">Numéro de téléphone</td>
            <td><span class="glyphicon glyphicon-earphone"></span> <?php echo $line['telephone']; ?></td>
        </tr>
        <tr>
            <td class="titre-tableau">Adresse e-mail</td>
            <td><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:<?php echo $line['mail']; ?>"><?php echo $line['mail']; ?></a></td>
        </tr>
    </table>

    <h2>Changer de mot de passe</h2>

    <form role="form" method="post" action="changer_mdp_submit">
        <div class="form-group col-sm-12">
            <label for="password_a">Mot de passe actuel : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock"> </span></div>
                <input id="password_a" name="password_a" type="password" class="form-control" placeholder="Mot de passe actuel" required>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <label for="password_n1">Nouveau mot de passe : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock"> </span></div>
                <input id="password_n1" name="password_n1" type="password" class="form-control" placeholder="Nouveau mot de passe" required>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <label for="password_n2">Nouveau mot de passe (encore) : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock"> </span></div>
                <input id="password_n2" name="password_n2" type="password" class="form-control" placeholder="Nouveau mot de passe (encore)" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Changer le mot de passe</button>
    </form>
</div>                 