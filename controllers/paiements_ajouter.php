<div class="col-lg-9">
		<?php
		    $templacat->set_variable("page_title", "Ajouter un paiement");
		
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
		    <p><a href="paiements_voir">« Retourner à la liste des paiements</a></p>
		    <h1>Ajouter un paiement</h1>
		                    
		    <form class="" role="form" method="post" action="paiements_ajouter_submit">
		        <div class="form-group col-sm-6">
		            <label for="devis">Devis concerné :</label>
		            <div class="input-group">
		                <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
		                <select id="devis" name="devis" class="form-control" required>
		                    <?php
		                        $query_list_devis_acceptes = $slim->pdo->query('SELECT TD.num_devis, TP.titre_projet
		                                                                        FROM ' . $config['db_prefix'] . 'T_Devis as TD
		                                                                        INNER JOIN ' . $config['db_prefix'] . 'T_Projet as TP
		                                                                        ON TD.num_projet = TP.num_projet
		                                                                        WHERE est_accepte = 1
		                                                                        ORDER BY TD.num_devis DESC');
		
		                        while($line_devis = $query_list_devis_acceptes->fetch())
		                            echo '<option value="' . $line_devis['num_devis'] . '">Devis n°' . $line_devis['num_devis'] . ' (' . $line_devis['titre_projet'] . ')</option>';
		                    ?>
		                </select>
		            </div>
		        </div>
		
		        <div class="form-group col-sm-3">
		            <label for="date_paiement">Date de paiement :</label>
		            <div class="input-group">
		                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		                <input id="date_paiement" name="date_paiement" type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" placeholder="JJ/MM/AAAA" required>
		            </div>
		        </div>
		
		        <div class="form-group col-sm-3">
		            <label for="quantite">Quantité payée (en <?php echo $config['currency']; ?>) :</label>
		            <div class="input-group">
		                <div class="input-group-addon"><span class="glyphicon glyphicon-euro"></span></div>
		                <input id="quantite" name="quantite" type="text" class="form-control" placeholder="En <?php echo $config['currency']; ?>" required>
		            </div>
		        </div>
		        
		        <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
		        <button type="submit" class="btn btn-success">Ajouter le paiement</button>
		    </form>
		</div></div>
