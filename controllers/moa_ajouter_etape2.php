<?php
    // Check if the id is a number, and sanitize it
    if(!is_numeric($_GET['cid']))
        die('Bad usage. $_GET[cid] should be a number!');
    else
        $_GET['cid'] = htmlspecialchars($_GET['cid']);

    if(!is_numeric($_GET['id_moa']))
        die('Bad usage. $_GET[id_moa] should be a number!');
    else
        $_GET['id_moa'] = htmlspecialchars($_GET['id_moa']);

    $templacat->set_variable("page_title", "Ajouter une maîtrise d'ouvrage (étape 2/2)");

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
    <h1>Ajouter une maîtrise d'ouvrage (étape 2/2)</h1>
    
    <form class="" role="form" method="post" action="moa_ajouter_submit">
        <div class="form-group col-sm-12">
            <label for="role">Rôle du MOA dans l'entreprise :</label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                <input id="role" name="role" type="text" class="form-control" placeholder="Rôle dans l'entreprise..." required>
            </div>
        </div>

        <input type="hidden" name="cid" value="<?php echo $_GET['cid']; ?>" />
        <input type="hidden" name="id_moa" value="<?php echo $_GET['id_moa']; ?>" />
                        
        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
        <button type="submit" class="btn btn-success">Lier le MOA et au client </button>
    </form>
</div>