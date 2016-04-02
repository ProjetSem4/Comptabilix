<?php
    session_start();
?>
<html>
    <head>
        <title>Installation - Fortitudo</title>
        <!-- Compiled and minified bootstrap CSS -->
        <link rel="stylesheet" href="../template/bootstrap/3.3.6/css/bootstrap.min.css" />

        <!-- Optional bootstrap theme -->
        <link rel="stylesheet" href="../template/bootstrap/3.3.6/css/bootstrap-theme.min.css" />

        <!-- Additionnal changes to bootstrap theme -->
        <link rel="stylesheet" href="../template/template.css">

        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <!-- Application header & navbar -->
        <nav class="navbar navbar-fixed-top navbar-custom">

            <!-- Container for centering content -->
            <div class="container">

                <!-- Application title -->
                <div class="navbar-header"><a class="navbar-brand logo" href="#">Fortitudo</a></div>

                <!-- Association name -->
                <div class="navbar-middle navbar-brand miseenpage2">Installation</div>
            </div>
        </nav>

        <!-- Application content -->
        <div class="container">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <?php
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
                    <form method="post" action="install.php">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title miseenpage3">Accès à la base de données MySQL</h3></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="db_host" class="control-label">Adresse du serveur : </label>
                                    <input class="form-control" name="db_host" required type="text">
                                </div>
                                <div class="form-group">
                                    <label for="db_user" class="control-label">Nom d'utilisateur : </label>
                                    <input class="form-control" name="db_user" required type="text">
                                </div>
                                <div class="form-group">
                                    <label for="db_pass" class="control-label">Mot de passe : </label>
                                    <input class="form-control" name="db_pass" type="password">
                                </div>
                                <div class="form-group">
                                    <label for="db_name" class="control-label">Base de données : </label>
                                    <input class="form-control" name="db_name" required type="text">
                                </div>
                                <div class="form-group">
                                    <label for="db_prefix" class="control-label">Préfixe des tables : </label>
                                    <input class="form-control" name="db_prefix" value="fortitudo_" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title miseenpage3">Paramètres généraux</h3></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="association_name" class="control-label">Nom de l'association : </label>
                                    <input class="form-control" name="association_name" required type="text">
                                </div>
                                <div class="form-group">
                                    <label for="currency" class="control-label">Devise à utiliser : </label>
                                    <input class="form-control" name="currency" value="€" placeholder="€, $, £, etc..." required type="text">
                                </div>
                                <div class="form-group">
                                    <label for="url" class="control-label">URL racine de Fortitudo : </label>
                                    <input class="form-control" name="url" required type="url" value="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . str_replace(array('/install/index.php', '/install/', '/install'), '', $_SERVER['DOCUMENT_URI']) . '/'; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title miseenpage3">Création d'un compte administrateur</h3></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="prenom">Prénom : </label>
                                    <input id="prenom" name="prenom" type="text" class="form-control"  placeholder="Prénom" required>
                                </div>   
                        
                                <div class="form-group">
                                    <label for="nom">Nom : </label>
                                    <input id="nom" name="nom" type="text" class="form-control" placeholder="Nom" required>
                                </div>
                           
                                <div class="form-group">
                                    <label for="adresse">Adresse : </label>
                                    <input id="adresse" name="adresse" type="adress" class="form-control" placeholder="Adresse">
                                </div>
                                
                                <div class="form-group">
                                    <label for="cp">Code postal :</label>
                                    <input id="cp" name="cp" type="text" class="form-control" placeholder="Code postal" required>
                                </div>
                        
                                <div class="form-group">
                                    <label for="ville">Ville :</label>
                                    <input id="ville" name="ville" type="text" class="form-control" placeholder="Ville" required>
                                </div>
                           
                                <div class="form-group">
                                    <label for="tel">Numéro de téléphone : </label>
                                    <input type="phone" name="tel" class="form-control" id="tel" placeholder="Téléphone">
                                </div>
                           
                                <div class="form-group">
                                    <label for="email">Adresse e-mail : </label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
                                </div>
                           
                                <div class="form-group">
                                    <label for="motdepasse">Mot de passe : </label>
                                    <input type="password" name="motdepasse" class="form-control" id="motdepasse" placeholder="Mot de passe">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Installer Fortitudo</button>
                    </form>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>
    </body>
</html>