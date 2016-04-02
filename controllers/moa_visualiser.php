<div class="col-lg-9">
		<?php
		    // Check if the id is a number, and sanitize it
		    if(!is_numeric($_GET['id']))
		        die('Bad usage. $_GET[id] should be a number!');
		    else
		        $_GET['id'] = $slim->pdo->quote($_GET['id']);
		
		    // Query the database
		    $query = $slim->pdo->query('SELECT * FROM ' . $config['db_prefix'] . 'V_MOA WHERE id_personne = ' . $_GET['id']);
		
		    // Check if the id is valid
		    if($query->rowCount() < 1)
		        die('Nothing found');
		
		    $line = $query->fetch();
		
		    $templacat->set_variable("page_title", "Détails pour " . $line['prenom'] . ' ' . $line['nom']);
		?>
		
		<div class="panel panel-default contenu-page">
		    <h1>Fiche MOA <a class="btn btn-warning pull-right" href="moa_editer?id=<?php echo $line['id_personne']; ?>"><span class="glyphicon glyphicon-pencil"></span> Éditer le MOA</a></h1>
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
		
		    <h3>Entreprises associées</h3>
		    <table class="table">
		        <?php
		            // List all the clients corresponding to the MOA
		            $query_clients = $slim->pdo->query('SELECT ' . $config['db_prefix'] . 'V_Societe.id_personne, ' . $config['db_prefix'] . 'V_Societe.raison_sociale, ' . $config['db_prefix'] . 'TJ_Societe_MOA.titre
		                                                FROM ' . $config['db_prefix'] . 'TJ_Societe_MOA
		                                                INNER JOIN ' . $config['db_prefix'] . 'V_Societe
		                                                ON ' . $config['db_prefix'] . 'TJ_Societe_MOA.id_societe = ' . $config['db_prefix'] . 'V_Societe.id_personne
		                                                WHERE ' . $config['db_prefix'] . 'TJ_Societe_MOA.id_MOA = ' . $_GET['id'] . '
		                                                ORDER BY ' . $config['db_prefix'] . 'V_Societe.id_personne DESC');
		
		            // If none is found
		            if($query_clients->rowCount() < 1)
		                echo '<tr><td colspan="5" style="text-align:center">Aucune entreprise associée n\'a pu être trouvée</td></td>';
		            
		            // Otherwise
		            else
		            {
		                echo '<tr><th>#</th><th>Raison sociale</th><th>Rôle</th><th style="width: 50px">Action</th></tr>';
		
		                while($client = $query_clients->fetch())
		                {
		                    echo '<tr>
		                        <td>' . $client['id_personne'] . '</td>
		                        <td>' . $client['raison_sociale'] . '</td>
		                        <td>' . $client['titre'] . '</td>
		                        <td><a class="btn btn-info" title="Visualiser le client" href="client_visualiser?id=' . $client['id_personne'] . '"><span class="glyphicon glyphicon-search"></span></a></td>
		                    </tr>';
		                }
		            }
		        ?>
		    </table>
		    </table>
		</div></div>
