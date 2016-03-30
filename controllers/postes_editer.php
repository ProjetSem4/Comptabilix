<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['id']))
        die('Bad usage. $_GET[id] should be a number!');
    else
        $_GET['id'] = $slim->pdo->quote($_GET['id']);

    // Query the database
    $query = $slim->pdo->query('SELECT * FROM T_Poste WHERE num_poste = ' . $_GET['id']);

    // Check if the id is valid
    if($query->rowCount() < 1)
        die('Nothing found');

    $line = $query->fetch();

    $templacat->set_variable("page_title", "Éditer " . $line['libelle']);

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
    <p><a href="postes_voir">« Retourner à la liste des postes</a></p>
    <h1>Éditer un poste</h1>
                    
    <form class="" role="form" method="post" action="postes_editer_submit">        
        <div class="form-group col-sm-12">
            <label for="libelle">Titre du poste :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
                <input id="libelle" name="libelle" type="text" class="form-control" placeholder="Titre du poste" value="<?php echo $line['libelle']; ?>" required>
            </div>
        </div>  

        <div class="form-group col-sm-12">
            <label for="tarif">Tarif horaire (en <?php echo $config['currency']; ?>) :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-euro"></span></div>
                <input id="tarif" name="tarif" type="text" class="form-control" placeholder="En <?php echo $config['currency']; ?>" value="<?php echo $line['tarif_horaire']; ?>" required>
            </div>
        </div>

        <input type="hidden" name="num_poste" value="<?php echo $line['num_poste']; ?>" />

        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Éditer le poste</button>
    </form>
</div>