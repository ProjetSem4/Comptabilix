<?php
    $templacat->set_variable("page_title", "Retrouver mon mot de passe");

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
    <h1>Retrouver mon mot de passe</h1>

    <form role="form" method="post" action="connexion_demande_nouveau_mdp">
        <div class="form-group col-sm-12">
            <label for="mail">Adresse e-mail du compte : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"> </span></div>
                <input id="mail" name="mail" type="email" class="form-control" placeholder="Adresse e-mail du compte" required>
            </div>
        </div>

        <button type="submit" class="btn btn-default">Demander un nouveau mot de passe</button>
    </form>
</div>