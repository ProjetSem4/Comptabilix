<div class="col-lg-9">
    <?php
        $templacat->set_variable("page_title", "Voir les clients");
    ?>
    <div class="panel panel-default contenu-page">
        <h1>Gestion des comptes</h1>
        <p>Retrouvez ici l'ensemble des documents relatifs aux comptes de votre association, classés par année.</p>

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
                        <a class="btn btn-success" title="Envoyer un nouveau document" href="comptes_uploader?a=' . $annee['annee'] . '"><span class="glyphicon glyphicon-cloud-upload"></span></a>
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
                    $tableau = '<tr><th>Nom du fichier</th><th>Date d\'envoi</th><th style="width:50px">Action</th></tr>';

                    // Compteur de fichiers pour l'année
                    $nbr_fichiers = 0;

                    // On ouvre le répertoire d'upload pour l'année
                    $repertoire = opendir('uploads/' . $annee['annee']);

                    // On parcours son contenu
                    while($contenu = readdir($repertoire)){
                        // Si c'est un fichier
                        if(!is_dir($contenu))
                        {
                            // Alors on l'affiche
                            $tableau .= '<tr>
                                <td>' . htmlspecialchars($contenu) . '</td>
                                <td>' . date('d/m/Y à H:i', filemtime('uploads/' . $annee['annee'] . '/' . $contenu)) . '</td>
                                <td><a class="btn btn-danger" title="Supprimer" href="comptes_supprimer?a=' . $annee['annee'] . '&fichier=' . urlencode($contenu) . '"><span class="glyphicon glyphicon-trash"></span></a></td>
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