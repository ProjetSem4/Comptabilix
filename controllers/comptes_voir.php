<div class="col-lg-9">
    <?php
        $templacat->set_variable("page_title", "Voir les comptes");

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
        <h1>Gestion des comptes</h1>
        <p>Retrouvez ici l'ensemble des documents relatifs aux comptes de votre association, classés par année.</p>

        <!-- Formulaire d'upload caché pour envoyer des fichiers via un click (utilisation de JQuery) -->
        <form action="comptes_uploader" method="post" enctype="multipart/form-data" id="formulaire_upload">
            <input type="file" name="fichier[]" multiple required id="formulaire_upload_fichier" />
            <input type="hidden" name="annee" required id="formulaire_upload_annee" />
        </form>

        <?php
            // Récupération de la liste des années pour lequelles des factures sont émises
            $query_liste_annee = $slim->pdo->query('SELECT DISTINCT YEAR(date_acceptation) as annee
                                                    FROM T_Devis
                                                    WHERE est_accepte = 1
                                                    ORDER BY annee DESC');

            // Affichage des années
            while($annee = $query_liste_annee->fetch())
            {
                // Titre pour l'année
                echo '<h2 class="page-header">
                    <span class="glyphicon glyphicon-calendar"></span> ' . $annee['annee'] . '
                    <div class="pull-right">
                        <a class="btn btn-success" title="Générer un compte de résultat" href="comptes_generer_cdr?a=' . $annee['annee'] . '"><span class="glyphicon glyphicon-stats"></span></a>
                        <a class="btn btn-success" title="Envoyer un nouveau document" href="#" onclick="upload_au_click(' . $annee['annee'] . ')"><span class="glyphicon glyphicon-cloud-upload"></span></a>
                    </div>
                </h2>';

                // Début du tableau
                echo '<table class="table">';

                // Si aucun dossier correspondant à l'année n'existe dans le répertoire d'upload, alors on affiche directement un message d'erreur
                if(!is_dir('uploads/' . $annee['annee']))
                    echo '<tr><td colspan="3" style="text-align:center;">Aucun document uploadé pour l\'année.</td></tr>';

                // Sinon
                else
                {
                    // On affiche le titre du tableau
                    $tableau = '<tr><th>Nom du fichier</th><th>Date d\'envoi</th><th style="width:100px">Actions</th></tr>';

                    // Compteur de fichiers pour l'année
                    $nbr_fichiers = 0;

                    // On ouvre le répertoire d'upload pour l'année
                    $repertoire = opendir('uploads/' . $annee['annee']);

                    // On parcours son contenu
                    while($contenu = readdir($repertoire)){
                        // Si c'est un fichier et qu'il n'est pas caché (= commencer par un point)
                        if(!is_dir($contenu) && substr($contenu, 0, 1) != '.')
                        {
                            // On prend le timestamp d'upload du fichier
                            $timestamp_fichier = end(explode('.', $contenu));

                            // On prend le nom du fichier, sans son timestamp final
                            $nom_fichier = str_replace('.' . $timestamp_fichier, '', $contenu);

                            // Puis on converti le timestamp string en long
                            $timestamp_fichier = $timestamp_fichier + 0;

                            // Alors on l'affiche
                            $tableau .= '<tr>
                                <td>' . htmlspecialchars($nom_fichier) . '</td>
                                <td>' . date('d/m/Y à H:i', $timestamp_fichier) . '</td>
                                <td>

                                    <a class="btn btn-info" title="Télécharger" href="comptes_telecharger?a=' . $annee['annee'] . '&fichier=' . urlencode($contenu) . '"><span class="glyphicon glyphicon-cloud-download"></span></a>
                                    <a class="btn btn-danger" title="Supprimer" href="comptes_supprimer?a=' . $annee['annee'] . '&fichier=' . urlencode($contenu) . '"><span class="glyphicon glyphicon-trash"></span></a>
                                </td>
                            </tr>';

                            // Et on incrémente le compteur de fichiers
                            $nbr_fichiers++;
                        }
                    }

                    // Puis on le referme
                    closedir($repertoire);

                    // Si aucune fichier n'a été affiché, on met un message d'erreur
                    if($nbr_fichiers == 0)
                        echo '<tr><td colspan="3" style="text-align:center;">Aucun document uploadé pour l\'année.</td></tr>';

                    // Sinon on affiche le tableau
                    else
                        echo $tableau;
                }

                echo '</table>';
            }
        ?>
    </div>
</div>
<script type="text/javascript" src="template/upload_click_bouton.js"></script>