
                <?php
				$templacat->set_variable("page_title", "Voir les clients");
				?>
                <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Succès : </strong>le client « SignaNet » a correctement été ajouté!
				</div>
                
                <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Succès : </strong>le client « SignaNet » a correctement été édité!
				</div>
                
                <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Succès : </strong>le client « SignaNet » a correctement été supprimé!
				</div>
                
                <div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Erreur : </strong>impossible de supprimer « Truc Muche » puisqu'il est associé à au moins un projet!
				</div>
                
                <div class="panel panel-default contenu-page">
                    <h1>Gestion des clients <a class="btn btn-success pull-right" href="clients_ajouter"><span class="glyphicon glyphicon-plus"></span> Ajouter un client</a></h1>
                    <p>Retrouvez ici l'ensemble des clients de votre association.</p>
                    <table class="table table-hover">
                        
                        <tr>
                            <th style="width: 30px">#</th>
                            <th>Raison sociale</th>
                            <th style="width: 150px">Projets</th>
                            <th style="width: 150px">Actions</th>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>SignaNet</td>
                            <td>0</td>
                            <td><a class="btn btn-info" title="Visualiser le client" href="clients_visualiser"><span class="glyphicon glyphicon-user"></span></a>
                            <a class="btn btn-warning" title="Éditer le client" href="clients_ajouter"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a class="btn btn-danger" title="Supprimer le client" href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                        <?php
                            for($i = 19; $i > 0; $i--)
                            {
                                echo '<tr>
                                    <td>' . $i . '</td>
                                    <td>Arbalètes discount</td>
                                    <td>' . mt_rand(1, 10) . '</td>
                                    <td><a class="btn btn-info" title="Visualiser le client"><span class="glyphicon glyphicon-user"></span></a>
                                    <a class="btn btn-warning" title="Éditer le client"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a class="btn btn-default disabled" title="Impossible de supprimer le client, puisqu\'il est associé à au moins un projet"><span class="glyphicon glyphicon-trash"></span></a></td>
                                </tr>';
                            }
                        ?>
                        <tr>
                            <th></th>
                            <th colspan="3">Total : 20 clients</th>
                        </tr>
                    </table>
                </div>
            