<?php
    $templacat->set_variable("page_title", "Connexion à Fortitudo");

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
    <h1>Connexion à Fortitudo</h1>

    <form role="form" method="post" action="connexion_submit">
        <div class="form-group col-sm-12">
            <label for="login">Nom d'utilisateur : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span></div>
                <input id="login" name="login" type="text" class="form-control" placeholder="Nom d'utilisateur" required>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <label for="password">Mot de passe : </label>
            <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock"> </span></div>
                <input id="password" name="password" type="password" class="form-control" placeholder="Mot de passe" required>
            </div>
        </div>

        <button type="submit" class="btn btn-default">Connexion</button>
</div>