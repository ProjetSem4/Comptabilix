<?php
    // Check if the cle is a passed, and sanitize it
    if(!isset($_GET['cle']))
        die('Bad usage. $_GET[cle] should be a number!');
    else
        $_GET['cle'] = htmlspecialchars($_GET['cle']);


    $templacat->set_variable("page_title", "Changer de mot de passe");

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
    <h2>Changer de mot de passe</h2>

    <form role="form" method="post" action="connexion_changer_mdp_submit">
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

        <input type="hidden" name="cle" value="<?php echo $_GET['cle']; ?>" />

        <button type="submit" class="btn btn-success">Changer le mot de passe</button>
    </form>
</div>