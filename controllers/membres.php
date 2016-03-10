				<?php
				$templacat->set_variable("page_title", "Ajouter les membres");
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
				<p><a href="membres_voir.php"><< Retourner à la liste des membres</a></p>
                    <h1>Ajouter un membre</h1>
					<form class="" role="form">
				
						<div class="form-group col-sm-6">
				  <!-- <p>
				   <label for="exampleInputIDNumber1">Numéro de membre : </label>
                            <input type="IDNumber" class="form-control" id="exampleInputIDNumber1" placeholder="IDNumber">
				   </p> -->
				   
				   
							<label for="exampleInputForename1">Prénom : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span></div>
								<input id="exampleInputForename1" type="text" class="form-control"  placeholder="Forename" required>
							</div>
						</div>
				   
						<div class="form-group col-sm-6">
				   
							<label for="exampleInputName1">Nom : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span> </div>
								<input id="exampleInputName1" type="text" class="form-control" placeholder="Name" required>
							</div>
						</div>
				   
						<div class="form-group col-sm-4">
				   
							<label for="exampleInputTitel1">Titre : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-king"> </span></div>
								<input id="exampleInputTitel1" type="text" class="form-control" placeholder="Titel" required>
							</div>
						</div>
				   
						<div class="form-group col-sm-8">
				   
							<label for="exampleInputAdress1">Adresse : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
								<input id="exampleInputAdress1" type="adress" class="form-control" placeholder="Adress">
							</div>
						</div>
						
						<div class="form-group col-sm-4">
							<label for="cp">Code postal :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
								<input id="cp" type="text" class="form-control" placeholder="Code postal" required>
							</div>
						</div>
						<div class="form-group col-sm-8">
							<label for="email">Ville :</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
								<input type="text" class="form-control" placeholder="Ville" required>
							</div>
						</div>
				   
						<div class="form-group col-sm-6">
							<label for="exampleInputPhone1">Téléphone : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></div>
								<input type="phone" class="form-control" id="exampleInputPhone1" placeholder="Phone">
							</div>
						</div>
				   
						<div class="form-group col-sm-6">
							<label for="exampleInputEmail1">Adresse mail : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
								<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
							</div>
						</div>
				   
						<!-- <div class="form-group col-sm-6">
							<label for="exampleInputActualProject1">Projet en cours : </label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>
				   
								<select type="ActuaProject" class="form-control" id="exampleInputActualProject1" placeholder="ActuaProject">
								<option> 1 </option>
								<option> 2 </option>
								</select>
								</div>
							</div> -->
				   
				   <button type="reset" class="btn btn-danger">Remettre à zéro le formulaire</button>
						<button type="submit" class="btn btn-success">Ajouter le membre</button>
				   
				  
				   </form>
				   
				   </div>

                    
                            
                   