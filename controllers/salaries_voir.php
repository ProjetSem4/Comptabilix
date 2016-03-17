
                <!-- <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Succès : </strong>le salarié « Truc Chose » a correctement été ajouté!
				</div>
                
                <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Succès : </strong>le salarié « Truc Chose » a correctement été édité!
				</div>
                
                <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Succès : </strong>le salarié « Truc Chose » a correctement été supprimé!
				</div>
                
                <div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Erreur : </strong>impossible de supprimer « Truc Muche » puisqu'il est associé à au moins un projet!
				</div> -->
                
                <div class="panel panel-default contenu-page">
                    <h1>Gestion des salariés <a class="btn btn-success pull-right" href="salaries_ajouter.php"><span class="glyphicon glyphicon-plus"></span> Ajouter un salarié</a></h1>
                    <p>Retrouvez ici l'ensemble des salariés de votre association.</p>
                    <table class="table table-hover">
                        
                        <tr>
                            <th style="width: 30px">#</th>
                            <th>Nom</th>
                            <th style="width: 150px">Projets</th>
                            <th style="width: 150px">Actions</th>
                        </tr>
                        <?php
						
							$templacat->set_variable("page_title", "Voir les salariés");
				
							$query = $slim->pdo->query("SELECT * FROM v_salarie");
				
							$incrementer_salarie = 0;
						//var_dump($query->fetchall());
                            while($line = $query->fetch())
                            {
								$incrementer_salarie++;
                                echo '<tr>
									<td>' . $line['id_personne'] . '</td>
									<td>'  . $line['prenom'] . ' ' . $line['nom'] . '</td>
									<td> 0 </td>
									<td><a class="btn btn-info" title="Visualiser le salarié" href="salaries_visualiser.php"><span class="glyphicon glyphicon-user"></span></a>
									<a class="btn btn-warning" title="Éditer le salarié" href="salaries_ajouter.php"><span class="glyphicon glyphicon-pencil"></span></a>
									<a class="btn btn-danger" title="Supprimer le salarié" href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
								</tr>';
                            }
                        ?>
                        <tr>
                            <th></th>
                            <th colspan="3">Total : <?= $incrementer_salarie; ?> salariés</th>
                        </tr>
                    </table>
                </div>
            
        
