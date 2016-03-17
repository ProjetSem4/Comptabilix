
                <?php
				$templacat->set_variable("page_title", "Voir un projet");
				?>
				
                <div class="alert alert-success" role="alert">
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
				</div>
                
                <div class="panel panel-default contenu-page">
                    <h1>Gestion des projets <a class="btn btn-success pull-right" href="projet"><span class="glyphicon glyphicon-plus"></span> Ajouter un projet</a></h1>
                    <p>Retrouvez ici l'ensemble des projets de votre association.</p>
                    <table class="table table-hover">
                        
                        <tr>
                            <th style="width: 30px">#</th>
                            <th>Nom</th>
                            <th style="width: 150px">Projets</th>
                            <th style="width: 150px">Actions</th>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>Quentin Bouteiller</td>
                            <td>0</td>
                            <td><a class="btn btn-info" title="Visualiser le projet" href="projet_visualiser"><span class="glyphicon glyphicon-user"></span></a>
                            <a class="btn btn-warning" title="Éditer le projet" href="projet"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a class="btn btn-danger" title="Supprimer le projet" href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                        <?php
                            for($i = 19; $i > 0; $i--)
                            {
                                echo '<tr>
                                    <td>' . $i . '</td>
                                    <td>Truc Machin</td>
                                    <td>' . mt_rand(1, 10) . '</td>
                                    <td><a class="btn btn-info" title="Visualiser le projet"><span class="glyphicon glyphicon-user"></span></a>
                                    <a class="btn btn-warning" title="Éditer le projet"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a class="btn btn-default disabled" title="Impossible de supprimer le membre, puisqu\'il est associé à au moins un projet"><span class="glyphicon glyphicon-trash"></span></a></td>
                                </tr>';
                            }
                        ?>
                        <tr>
                            <th></th>
                            <th colspan="3">Total : 20 projets</th>
                        </tr>
                    </table>
                </div>
            </div>
			<div class="text-center">
			<ul class="pagination">
  <li><a href="#">1</a></li>
  <li class="active"><a href="#">2</a></li>
  <li><a href="#">3</a></li>
  <li><a href="#">4</a></li>
  <li><a href="#">5</a></li>
</ul>
</div>
    
    