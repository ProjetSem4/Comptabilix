
                <?php
				$templacat->set_variable("page_title", "Ajouter un projet");
				?>
				
                <!-- <div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Vert !</strong> Message.
				</div>
						
				<div class="alert alert-info" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Bleu !</strong> Message.
				</div>
						
				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Jaune !</strong> Message.
				</div>
						
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Rouge !</strong> Message.
				</div> -->
                
                <div class="panel panel-default contenu-page">
				<p><a href="projet_voir.php">« Retourner à la liste des projets</a></p>
                    <h1>Projets</h1>
					<h4> Entrer les données du nouveau projet </h4>
					<p> </br> </p>

                    <form>
					<div class="checkbox">
					  <label><input type="checkbox" value=""> Projet</label>
					</div>
					<div class="checkbox">
					  <label><input type="checkbox" value="">Formation</label>
					</div>
					
                        <div class="form-group">
						
                           
                            <label for="exampleInputTitel1">Titre</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-folder-open"> </span></div>
								
                            <input type="Titel" class="form-control" id="exampleInputTitre1" placeholder="Titel">
							</div>
                            <label for="exampleInputDateOfCreation">Date de création</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"> </span></div>
                            <input type="DateOfCreation" class="form-control" id="exampleInputDateOfCreation1" placeholder="Date of creation">
							</div>
							<label for="comment">Description:</label>
							<textarea class="form-control" rows="5" id="comment" placeholder="Description of project"></textarea>
                           
                        
		
				   
				  
				   </div>
				   
				   
                    </form>
                </div>
            