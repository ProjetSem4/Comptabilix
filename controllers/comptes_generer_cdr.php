<?php
    // Commençons par vérifier si l'utilisation est correcte
    if(!isset($_GET['a']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Mauvais usage du générateur.');
    
    // L'année est-elle bien un nombre?
    elseif(!is_numeric($_GET['a']))
        $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'L\'année doit être un nombre.');

    // Si tout est ok
    else
    {
        // 5 minutes pour générer le document
        set_time_limit(300);

        // Et 2Go de mémoire vive alloues
        ini_set('memory_limit', '2048M');

        // Paramètres
        $annee = $_GET['a'];
        $ligne_debut_produits = 3;
        $ligne_debut_rcs = 3;
        $ligne_debut_projet_liste_postes = 4;
        $decalage_lignes_projets = array(); // Contient les décalages pour les lignes de postes dans les feuilles de projet
        $projets_employes = array(); // Contient les informations sur les salariés pour chaque projet (nom et rémunération brute)
        $colonne_debut_personnes = 'G';
        $colonne_debut_tableau = 3;

        // Ouverture en lecture du fichier XLSX de comptabilité (si existe)
        if(!file_exists('models/cdr.xlsx'))
            $_SESSION['fortitudo_messages'][] = array('type' => 'error', 'content' => 'Fichier de modèle introuvable.');
        else
        {
            $modele_xlsx = PHPExcel_IOFactory::load('models/cdr.xlsx');

            // Modification des « Rémunérations & Charges Sociale » (rémunérations des salariés) et ajout des feuilles de projet

                // Récupération des postes terminés pour l'année courante
                $query_rcs = $slim->pdo->query('SELECT Tcalc.num_devis, TPRJ.titre_projet, TP.libelle, TP.part_salariale, TDSP.nbr_heures as heures_passees, VS.nom, VS.prenom
                                                FROM 
                                                (
                                                    SELECT TD.num_devis, TD.num_projet, SUM(TDSP.nbr_heures * TP.tarif_horaire) as cout_total, SUM(TPAI.quantite_payee) as quantite_payee, MAX(TPAI.date_paiement) as dernier_paiement
                                                    FROM ' . $config['db_prefix'] . 'T_Devis TD
                                                    INNER JOIN ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste TDSP
                                                    ON TD.num_devis = TDSP.num_devis
                                                    INNER JOIN ' . $config['db_prefix'] . 'T_Poste TP
                                                    ON TDSP.num_poste = TP.num_poste
                                                    INNER JOIN ' . $config['db_prefix'] . 'T_Paiement as TPAI
                                                    ON TD.num_devis = TPAI.num_devis
                                                    GROUP BY TD.num_devis
                                                    ORDER BY dernier_paiement ASC
                                                ) as Tcalc
                                                INNER JOIN ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste as TDSP
                                                ON Tcalc.num_devis = TDSP.num_devis
                                                INNER JOIN ' . $config['db_prefix'] . 'T_Poste TP
                                                ON TDSP.num_poste = TP.num_poste
                                                INNER JOIN ' . $config['db_prefix'] . 'V_Salarie as VS
                                                ON TDSP.id_personne = VS.id_personne
                                                INNER JOIN ' . $config['db_prefix'] . 'T_Projet as TPRJ
                                                ON Tcalc.num_projet = TPRJ.num_projet
                                                WHERE YEAR(Tcalc.dernier_paiement) = ' . $slim->pdo->quote($annee));

                // Insertion des données dans le fichier
                $ligne_rcs = $ligne_debut_rcs;
                $sheet_rcs = $modele_xlsx->getSheetByName('Rémunérations & Charges Sociale');

                while($rcs = $query_rcs->fetch())
                {
                    // Conversion du prix, pour rajouter une virgue si besoin
                    $prix = $rcs['part_salariale'];
                    if(strpos($prix, '.') == false)
                        $prix .= '.00';

                    // Insertion de la ligne dans « Rémunérations & Charges Sociale »
                    $sheet_rcs->setCellValue('B' . $ligne_rcs, $rcs['prenom'] . ' ' . $rcs['nom']);
                    $sheet_rcs->setCellValue('C' . $ligne_rcs, $rcs['titre_projet']);
                    $sheet_rcs->setCellValue('D' . $ligne_rcs, $rcs['heures_passees']);
                    $sheet_rcs->setCellValue('E' . $ligne_rcs, $rcs['part_salariale']);
                    $sheet_rcs->setCellValue('G' . $ligne_rcs, '=D' . $ligne_rcs . '*E' . $ligne_rcs);

                    // Vérifions si une feuille au nom du projet existe déjà
                    $titre_feuille = 'Projet ' . substr($rcs['titre_projet'], 0, 24); // Les titres sont limités à 31 caractères...

                    // Si elle n'existe pas
                    if(!$modele_xlsx->sheetNameExists($titre_feuille))
                    {
                        // On clone notre sheet « modèle » pour le projet
                        $sheet_projet_tmp = clone $modele_xlsx->getSheetByName('Projet');

                        // Puis on le renomme
                        $sheet_projet_tmp->setTitle($titre_feuille);

                        // Et enfin on l'insère dans le fichier original
                        $modele_xlsx->addSheet($sheet_projet_tmp);

                        // Définition d'un décalage pour l'affichage
                        $decalage_lignes_projets[$titre_feuille] = 0;

                        // Définition d'un nouveau tableau contenant les informations de comptabilité pour les employés
                        $projets_employes[$titre_feuille] = array();
                    }

                    // On récupère la feuille
                    $sheet_projet = $modele_xlsx->getSheetByName($titre_feuille);
                    
                    // On change le titre affiché en haut de la feuille
                    $sheet_projet->setCellValue('C2', 'Projet ' . $rcs['titre_projet']);

                    // Puis on créé une nouvelle ligne dans le tableau listant les postes du projet
                    $sheet_projet->insertNewRowBefore(($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]), 1);

                    // Et on la remplit de données
                    $sheet_projet->setCellValue('C' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]), $rcs['libelle']);
                    $sheet_projet->setCellValue('D' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]), $rcs['heures_passees']);
                    $sheet_projet->setCellValue('E' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]), $rcs['part_salariale']);
                    $sheet_projet->setCellValue('F' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]), $rcs['prenom'] . ' ' . $rcs['nom']);

                    // Insertion du total étudiant
                    $sheet_projet->setCellValue('D' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille] + 1), '=SUM(D' . $ligne_debut_projet_liste_postes . ':D' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]) . ')');

                    // Si la personne n'a pas encore été insérée dans le tableau associatif
                    if(!isset($projets_employes[$titre_feuille][$rcs['prenom'] . ' ' . $rcs['nom']]))
                        $projets_employes[$titre_feuille][$rcs['prenom'] . ' ' . $rcs['nom']] = '=D' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]) . '*E' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]);
                    else
                        $projets_employes[$titre_feuille][$rcs['prenom'] . ' ' . $rcs['nom']] .= '+D' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]) . '*E' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille]);

                    // On descend d'une ligne
                    $decalage_lignes_projets[$titre_feuille]++;
                    $ligne_rcs++;
                }

                // Chiffre => lettres
                $lettres = array_combine(range(1,26), range('a', 'z'));

                // Pour chaque projet précédement traité
                foreach($projets_employes as $titre_feuille_projet => $un_projet)
                {
                    // On récupère la bonne feuille
                    $sheet_projet = $modele_xlsx->getSheetByName($titre_feuille_projet);

                    // On reset les valeurs
                    $colonne_debut_personnes = 'G';
                    $colonne_debut_tableau = 3;

                    // Calcul final pour le coût total du travail pour l'association
                    $calcul_cout_total = '=G' . strtoupper($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 23) . '+';

                    // Génération des tableaux
                    foreach($un_projet as $employe => $formule)
                    {
                        // On insère un tableau permettant de calculer les différentes charges sociales (si deuxième employé ou plus)
                        if($colonne_debut_tableau > 3)
                        {
                            // On duplique le contenu
                            $cellules_tableau = $sheet_projet->rangeToArray('C' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2) . ':H' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 24));
                            $sheet_projet->fromArray($cellules_tableau, null, strtoupper($lettres[$colonne_debut_tableau]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2));

                            // Puis le style
                            // Pour chaque colonne du tableau
                            for($col = 0; $col < 7; $col++)
                            {
                                // Et pour chaque ligne du tableau
                                for($lin = 0; $lin < 23; $lin++)
                                {
                                    // On récupère le style de la case modèle
                                    $style_original = $sheet_projet->getStyle(
                                        strtoupper($lettres[3 + $col]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2 + $lin)
                                    );
                                    
                                    // Et on le duplique dans la case dupliquée
                                    $style_original = $sheet_projet->duplicateStyle(
                                        $style_original,
                                        strtoupper($lettres[$colonne_debut_tableau + $col]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2 + $lin)
                                    );
                                }
                            }

                            // Puis on fusionne les cellules devant être fusionnées
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2) . ':' . strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3) . ':' . strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));

                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17) . ':' . strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17));

                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 18) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 18));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 19) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 19));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 20) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 20));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 21) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 21));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 22) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 22));
                            $sheet_projet->mergeCells(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 23) . ':' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 23));

                            // Injection de la colonne dans le calcul du coût total
                            $calcul_cout_total .= strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 23) . '+';

                            // On ré-injecte les les pourcentages (mal interprétés après la copie)
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5), 0.01);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6), 0.068);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8), 0.024);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10), 0.0385);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 13), 0.051);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 14), 0.029);

                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5), 0.212);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6), 0.0845);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8), 0.048);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10), 0.0578);
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 16), 0.1);

                            // Et enfin on injecte les formules de calcul (la copie les remplace par les valeurs)
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 13), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3) . '*(1 - A2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 14), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3) . '*(1 - A2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 16), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));

                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5) . '*' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6) . '*' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8) . '*' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10) . '*' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 13), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 13) . '*' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 13) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 14), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 14) . '*' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 14) . ';2)');

                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5) . '*' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 5) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6) . '*' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 6) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8) . '*' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 8) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10) . '*' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 10) . ';2)');
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 5]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 16), '=ROUNDUP(' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 16) . '*' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 16) . ';2)');

                            // On ré-injecte les formules de calcul des taux situés sous le tableau
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 18), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 19), '=' . strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3) . '-' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 20), '=' . strtoupper($lettres[$colonne_debut_tableau + 3]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 14) . '+' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 19));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 21), '=' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17) . '+' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 22), '=' . strtoupper($lettres[$colonne_debut_tableau + 2]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 17));
                            $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 23), '=' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 18) . '+' . strtoupper($lettres[$colonne_debut_tableau + 4]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 22));
                        }

                        // Enfin, on change les valeurs
                        $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 2), $employe);
                        $sheet_projet->setCellValue(strtoupper($lettres[$colonne_debut_tableau + 1]) . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 3), $formule);

                        // Et on incrémente les lettres
                        $colonne_debut_personnes++;
                        $colonne_debut_tableau += 7;
                    }
                    // On ajoute la nouvelle colonne au coût total (après l'avoir « nettoyé » de son dernier « + »)
                    $calcul_cout_total = rtrim($calcul_cout_total, '+');

                    $sheet_projet->setCellValue('G' . ($ligne_debut_projet_liste_postes + $decalage_lignes_projets[$titre_feuille_projet] + 25), $calcul_cout_total);
                }

                // Suppression du modèle pour les projets
                $modele_xlsx->removeSheetByIndex($modele_xlsx->getIndex($modele_xlsx->getSheetByName('Projet')));

            // Modification des produits associés
                
                // Récupération des factures liées aux devis acceptés, émises sur l'année donnée
                $query_factures = $slim->pdo->query('SELECT TD.num_devis, TD.date_acceptation, SUM(TDSP.nbr_heures * TP.tarif_horaire) as pt, TPJ.titre_projet
                                                FROM ' . $config['db_prefix'] . 'T_Devis as TD
                                                INNER JOIN ' . $config['db_prefix'] . 'TJ_Devis_Salarie_Poste as TDSP
                                                ON TD.num_devis = TDSP.num_devis
                                                INNER JOIN ' . $config['db_prefix'] . 'T_Poste as TP
                                                ON TDSP.num_poste = TP.num_poste
                                                INNER JOIN ' . $config['db_prefix'] . 'T_Projet as TPJ
                                                ON TD.num_projet = TPJ.num_projet
                                                WHERE YEAR(TD.date_acceptation) = ' . $slim->pdo->quote($annee) . '
                                                GROUP BY TD.num_devis
                                                ORDER BY TD.date_acceptation DESC');

                // Insertion des factures liées aux devis
                $ligne_facture = $ligne_debut_produits;
                $sheet_produits = $modele_xlsx->getSheetByName('Produits');
                while($facture = $query_factures->fetch())
                {
                    // Conversion du prix, pour rajouter une virgue si besoin
                    $prix = $facture['pt'];
                    if(strpos($prix, '.') == false)
                        $prix .= '.00';

                    // Insertion de la ligne
                    $sheet_produits->setCellValue('A' . $ligne_facture, date('d/m/Y', strtotime($facture['date_acceptation'])));
                    $sheet_produits->setCellValue('B' . $ligne_facture, 'Facture projet');
                    $sheet_produits->setCellValue('C' . $ligne_facture, 'Projet ' . $facture['titre_projet']);
                    $sheet_produits->setCellValue('D' . $ligne_facture, 'Devis accepté n°' . $facture['num_devis']);
                    $sheet_produits->setCellValue('F' . $ligne_facture, $prix);

                    // Puis on descend d'un cran
                    $ligne_facture++;
                }

                // Récupération des factures liées aux services en cours
                $query_factures = $slim->pdo->query('SELECT
                                                    TDS.num_devis,
                                                    TS.libelle,
                                                    TS.tarif_mensuel,
                                                    YEAR(TDS.date_debut) as annee_debut,
                                                    MONTH(TDS.date_debut) as mois_debut,
                                                    DAY(TDS.date_debut) as jour_debut,
                                                    YEAR(TDS.date_fin) as annee_fin,
                                                    MONTH(TDS.date_fin) as mois_fin
                                                FROM ' . $config['db_prefix'] . 'TJ_Devis_Service as TDS
                                                INNER JOIN ' . $config['db_prefix'] . 'T_Service as TS
                                                ON TS.num_service = TDS.num_service
                                                WHERE
                                                    YEAR(TDS.date_debut) <= ' . $annee . ' AND (TDS.date_fin IS NULL OR YEAR(TDS.date_fin) <= ' . $annee . ')
                                                ORDER BY num_devis DESC');

                // Insertion des factures liées aux devis
                while($facture = $query_factures->fetch())
                {
                    // Correction des dates de fin
                    if(is_null($facture['annee_fin']))
                        $facture['annee_fin'] = $annee;

                    if(is_null($facture['mois_fin']))
                        $facture['mois_fin'] = 12;

                    // Correction du mois de début
                    if($facture['annee_debut'] != $annee)
                        $facture['mois_debut'] = 1;

                    // Conversion du prix, pour rajouter une virgue si besoin
                    $prix = $facture['tarif_mensuel'];
                    if(strpos($prix, '.') == false)
                        $prix .= '.00';

                    // On boucle pour tous les mois de validité de la facture
                    for($i = $facture['mois_debut']; $i <= $facture['mois_fin']; $i++)
                    {
                        // Ajout du zéro initial sur le jour (si besoin)
                        $jour_facturation_zero_initial = $facture['jour_debut'];
                        if($jour_facturation_zero_initial < 10)
                            $jour_facturation_zero_initial = '0' . $jour_facturation_zero_initial;

                        // Ajout du zéro initial sur le mois (si besoin)
                        $mois_facturation_zero_initial = $i;
                        if($mois_facturation_zero_initial < 10)
                            $mois_facturation_zero_initial = '0' . $mois_facturation_zero_initial;

                        // Insertion de la ligne
                        $sheet_produits->setCellValue('A' . $ligne_facture, $jour_facturation_zero_initial . '/' . $mois_facturation_zero_initial . '/' . $annee);
                        $sheet_produits->setCellValue('B' . $ligne_facture, 'Facture service');
                        $sheet_produits->setCellValue('C' . $ligne_facture, 'Service ' . $facture['libelle']);
                        $sheet_produits->setCellValue('D' . $ligne_facture, 'Devis accepté n°' . $facture['num_devis']);
                        $sheet_produits->setCellValue('F' . $ligne_facture, $prix);

                        // Puis on descend d'un cran
                        $ligne_facture++;
                    }
                }

            // Ré-écriture des métadonnées du fichier
                $modele_xlsx->getProperties()
                            ->setCreator('Fortitudo')
                            ->setLastModifiedBy('Fortitudo')
                            ->setTitle('Comptabilité ' . $annee)
                            ->setSubject('Comptabilité ' . $annee . ' pour l\'association ' . $config['association'])
                            ->setDescription('Document comptable préparant le bilan de l\'association ' . $config['association'] . ' pour l\'année ' . $annee . ', produit par Fortitudo.');

            // Écriture du fichier XLSX
                $xlsx_export = PHPExcel_IOFactory::createWriter($modele_xlsx, 'Excel2007');
                $xlsx_export->setPreCalculateFormulas(false); // On ne re-calculera pas les formules

                // On commence par le sauvegarder (en créant les dossiers si besoin)
                if(!is_dir('uploads/'))
                    mkdir('uploads');

                if(!is_dir('uploads/' . $annee))
                    mkdir('uploads/' . $annee);

                $xlsx_export->save('uploads/' . $annee . '/Compte_de_resultats_' . $annee . '.xlsx');

                $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Compte de résultats pour l\'année ' . $annee . ' généré avec succès. N\'oubliez pas de presser CTRL + SHIFT + F9 pour lancer les calculs.');
        }
    }
    header('Location: comptes_voir');
?>