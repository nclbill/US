<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }
?>

	<?php
	$mode = 0; // 0 saisie, 1 mode modification
	$id_client = isset($_GET['id_client']) ? $_GET['id_client'] : null; // Si id_client est passé en GET, sinon null
	$lname = isset($_GET['lname']) ? htmlspecialchars($_GET['lname'], ENT_QUOTES, 'UTF-8') : '';
	$fname = isset($_GET['fname']) ? htmlspecialchars($_GET['fname'], ENT_QUOTES, 'UTF-8') : '';
	$raison_sociale = isset($_GET['raison_sociale']) ? htmlspecialchars($_GET['raison_sociale'], ENT_QUOTES, 'UTF-8') : '';
	$ice = isset($_GET['ice']) ? htmlspecialchars($_GET['ice'], ENT_QUOTES, 'UTF-8') : '';
	$habilitation = isset($_GET['habilitation']) ? htmlspecialchars($_GET['habilitation'], ENT_QUOTES, 'UTF-8') : '';
	$cin_pass = isset($_GET['cin_pass']) ? htmlspecialchars($_GET['cin_pass'], ENT_QUOTES, 'UTF-8') : '';
	$email = isset($_GET['email']) ? htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8') : '';
	$tel = isset($_GET['tel']) ? htmlspecialchars($_GET['tel'], ENT_QUOTES, 'UTF-8') : '';
	$magasin = isset($_GET['magasin']) ? htmlspecialchars($_GET['magasin'], ENT_QUOTES, 'UTF-8') : '';
	$ville = isset($_GET['ville']) ? htmlspecialchars($_GET['ville'], ENT_QUOTES, 'UTF-8') : '';
	$date = date("Y/m/d");
	$username = $cin_pass; // Le username sera la cin_pass
	$searchTerm = isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm'], ENT_QUOTES, 'UTF-8') : ''; // Recherche par mot-clé
	$password = "123456";
	$prompt_ncl = randomstring(15);







	if ($habilitation == "Client") {
	$permissions = 6;
	}
	if ($habilitation == "Utilisateur") {
	$permissions = 1;
	}



	if(!empty($_GET['Ajouter'])){

	  if (empty($cin_pass)){

						//		 $validate = new Validate();
						//		 $validate->addError("cin Obligatoire");
						//		 dump($validate->errors());
						$err = array("cin Obligatoire");
	          	display_errors($err);
	} else {
		$query = $db->query("SELECT id FROM users WHERE cin_pass = ? ",[$cin_pass]);
		$count = $query->count();
		$results = $query->results();




		if($count >= 1){
			$err = array("cin existe deja parmi vos users");
				display_errors($err);
	//		err("cin existe deja parmi vos users");
			} else {
				if (empty($email)){
					$err = array("email Obligatoire");
						display_errors($err);
			//	 err("email Obligatoire");
					 } else {
			     if (empty($lname)){
				     $err = array("Nom Obligatoire");
				      	display_errors($err);
		//	err("Nom Obligatoire");
			       } else {
			      	if (empty($fname)){
				      	$err = array("Prenom Obligatoire");
				      		display_errors($err);
		//		err("fname Obligatoire");
	} else {
		if (empty($raison_sociale)){
			$err = array("Raison sociale Obligatoire");
				display_errors($err);
//		err("fname Obligatoire");
			} else {
				if (empty($ice)){
					$err = array("ICE Obligatoire");
						display_errors($err);
//		err("fname Obligatoire");
			        	} else {
			       		if (empty($tel)){
					     	$err = array("Telephone Obligatoire");
					    		display_errors($err);
			//		err("fname Obligatoire");
				       	} else {
				       	if (empty($magasin)){
				     		 $err = array("magasin Obligatoire");
								  display_errors($err);
			//			err("magasin Obligatoire");
					       } else {
					       	if (empty($ville)){
							    	$err = array("Ville Obligatoire");
							    		display_errors($err);
				//			err("magasin Obligatoire");
				          	} else {
			            		if (empty($habilitation)){
				            		$err = array("Habilitation Obligatoire");
				            			display_errors($err);
			//		err("habilitation Obligatoire");
				             	} else {


             $prompt_ncl = randomstring(15);

							$fields1 = array(

											"permissions" => $permissions,
											"email" => $email,
											"username"=> $username,
											//"password" => "123456",
											"fname" => $fname,
											"lname" => $lname,
											"raison_sociale" => $raison_sociale,
											"ice" => $ice,
											"cin_pass" => $cin_pass,
											"tel" => $tel,
											"habilitation" => $habilitation,
											"vericode" => 0,
											"vericode_expiry" => $password,
											"oauth_tos_accepted" => true,
											"email_verified" => 1,
											"account_owner" => 1,
											"join_date" => $date,
											"active" => 1,
											"ville" => $ville,
											"magasin" => $magasin,
											"prompt_ncl"=>$prompt_ncl,
											"password" => password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)),
											);


							$db->insert("users",	$fields1);

							$query = $db->query("SELECT * FROM users WHERE prompt_ncl = ?",[$prompt_ncl]);
							$count = $query->count();
							$results = $query->results();
							$id_user = $results[0]->id;


         ////// insertion dans la table permission

										$db->insert("user_permission_matches", ["user_id"=>$id_user,"permission_id"=>$permissions]);
										$db->insert("user_permission_matches", ["user_id"=>$id_user,"permission_id"=>1]);

										//// supression de prompt_ncl
									$db->update("users",$id_user,["prompt_ncl" => 0]);




									Redirect::to("clients.php");
									logger($user->data()->id, "Ajout client", "a ajoute $nom $code_interne");


}}}}}}}}}
}
}}

	?>





<?php
////////////////////////////////////////search /////////////////////////////////////////////////////////////
	if(!empty($_GET['search'])){
		$searchTerm = Input::get('search');
	}

  $query_client = $db->query("SELECT * FROM users  WHERE cin_pass = ? and permissions = ? or cin_pass = ? and permissions = ?",[$searchTerm,1,$searchTerm,6]);
	//$query_client = $db->query("SELECT * FROM users WHERE cin_pass = ?",[$searchTerm]);
	$count_client = $query_client->count();
	$results_client = $query_client->results();

//print_r($results_client);


	//logger($user->data()->id, "Search article", "Searched for $searchTerm");

	if ($count_client > 0) {
	$mode = 1;// 0 saisie 1 mode modificationn

$id_user = $results_client[0]->id;
$cin_pass = $results_client[0]->cin_pass;
$lname = $results_client[0]->lname;
$fname = $results_client[0]->fname;
$raison_sociale = $results_client[0]->raison_sociale;
$ice= $results_client[0]->ice;
$habilitation = $results_client[0]->habilitation;
$magasin = $results_client[0]->magasin;
$email = $results_client[0]->email;
$tel = $results_client[0]->tel;
$ville = $results_client[0]->ville;
	}

	?>


	<div class="row">
		<div  class="col-sm-12">

			<h2 align="center">Cilent</h2> <!--"  "-->
		<p align="center">
			<form class="" action="" method="get">
				<label for=""></label>
				<div class="">

				<label for="">Cin Passport</label>
				<input class="form-control" type="text" name="cin_pass" value="<?=$cin_pass?>" optional  autofocus="on" placeholder="cin_pass">
				<label for="">Email</label>
				<input class="form-control" type="text" name="email" value="<?=$email?>" optional  autofocus="on" placeholder="email">
				<label for="">Nom du Client</label>
				<input class="form-control" type="text" name="lname" value="<?=$lname?>" optional  autofocus="on" placeholder="nom">
				<label for="">Prenom du Client</label>
				<input class="form-control" type="text" name="fname" value="<?=$fname?>" optional  autofocus="on" placeholder="prenom">
				<label for="">R Sociale</label>
				<input class="form-control" type="text" name="raison_sociale" value="<?=$raison_sociale?>" optional  autofocus="on" placeholder="raison_sociale">
				<label for="">ICE</label>
				<input class="form-control" type="text" name="ice" value="<?=$ice?>" optional  autofocus="on" placeholder="ice">
				<label for="">Telephone</label>
				<input class="form-control" type="text" name="tel" value="<?=$tel?>" optional  autofocus="on" placeholder="tel">
				<label for="">magasin</label>
				<input class="form-control" type="text" name="magasin" value="<?=$magasin?>" optional  autofocus="on" placeholder="">
				<label for="">ville</label>
				<input class="form-control" type="text" name="ville" value="<?=$ville?>" optional  autofocus="on" placeholder="ville">
				<label for="">Habilitation</label>
				<br>
				<select id="habilitation" name="habilitation">

	      <option value="<?=$habilitation?>"><?=$habilitation?></option>
				 		<option value="Utilisateur">Utilisateur</option>
						<option value="Client">Client</option>

        </select>
				<br>

				<br>

		<input class="form-control" type="hidden" name="id_client" value="<?=$id_client?>" optional  autofocus="on" placeholder="id_client">
		<input class="form-control" type="hidden" name="searchTerm" value="<?=$searchTerm?>" optional  autofocus="on" placeholder="searchTerm">



<!-- //////////////////////////////////// les boutons	-->
					 <input class="btn btn-success" type="submit" name="Ajouter" value="Ajouter">
		 		 <?php if ($mode == 1): // 0 saisie 1 mode modificationn?>
					 	<input class="btn btn-success" type="submit" name="Modifier" value="Modifier">
						<input class="btn btn-success" type="submit" name="Supprimer" value="Supprimer">


					 <?php endif; ?>

	<!--  ////////////////////////////////////  fin les boutons	-->
					 </div>
				 </form>

		 </div>


			<div class="row">
				<!-- left col -->

				<!-- right col -->
				<div  class="col-sm-12">
					<h2>Rechercher Un client</h2>
					<form class="" action="" method="get">
						<label for="">Entez La CIN ou le Passport</label>
						<div class="input-group">
				  	<input class="form-control" type="hidden" name="id_client" value="<?=$id_client?>" required  autofocus="on" placeholder="id_client">
						<input class="form-control" type="text" name="search" value="<?=$searchTerm?>"required autofocus="on" placeholder="Search Here!">
						<input class="btn btn-success" type="submit" name="submit" value="Go!">

						</div>
					</form>
				</div>
			</div>
			<?php

/*	 */?>

<?php 			$query_all_client = $db->query("SELECT * FROM users  WHERE  permissions = ? or permissions = ?",[1,6]);
				 		$count_all_client = $query_all_client->count();
				 		$results_all_client = $query_all_client->results();
				 	//	$id_client = $results_client[0]->id;
					//	$id_user = $results_client[0]->id_user;

//print_r($results_all_client);
					?>


				<div class="row">
					<div class="col-sm-12">
						<br>
						<h2>vous avez <?=$count_all_client?> client<?php if($count_all_client != 1){ echo "s";}	?>
						</h2>

						<table class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Prenom</th>
									<th>nom</th>
									<th>R Sociale</th>
									<th>ICE</th>
									<th>cin_pass</th>
									<th>tel</th>
									<th>Email</th>
									<th>Fonction</th>
									<th>magasin</th>
									<th>ville</th>


								</tr>
							</thead>
							<tbody>
								<?php foreach ($results_all_client as $r) { ?>
									<tr>
										<td><?=$r->id?></td>
										<td><?=$r->fname?></td>
										<td><?=$r->lname?></td>
										<td><?=$r->raison_sociale?></td>
										<td><?=$r->ice?></td>
										<td><?=$r->cin_pass?></td>
										<td><?=$r->tel?></td>
										<td><?=$r->email?></td>
										<td><?=$r->habilitation?></td>
										<td><?=$r->magasin?></td>
										<td><?=$r->ville?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>

					</div>
				</div>



<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
