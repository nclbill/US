<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }
	$mode = 0;// 0 saisie 1 mode modification
  $id_collaborateur = Input::get('id_collaborateur');
	$lname = Input::get('lname');
	$fname = Input::get('fname');
	$habilitation = Input::get('habilitation');
	$cin_pass  = Input::get('cin_pass');
	$email = Input::get('email');
	$tel = Input::get('tel');
	$magasin  = Input::get('magasin');
	$ville = Input::get('ville');
	$date = date("Y/m/d");
	$username= $cin_pass;// le username sera la cin_pass
  $searchTerm = Input::get('searchTerm');
  $password = randomstring(6);
	$subject = "votre mot de pass @EspaceMoto";
	$body = "login:".$username."   /   pass:   ".$password;
  $prompt_ncl = randomstring(15);


  if ($habilitation == "OpSaisie") {
	$permissions = 4;
	}
	if ($habilitation == "Controleur") {
	$permissions = 5;
	}
	if ($habilitation == "Commercial") {
	$permissions = 7;
	}
	if ($habilitation == "Gestionnaire") {
	$permissions = 3;
	}
	if ($habilitation == "OpSaisie+++") {
	$permissions = 8;
	}
	////////////////////////////////// ajouter sécurisé
	if (!empty($_GET['Ajouter'])) {
	    $err = [];

	    if (empty($cin_pass)) {
	        $err[] = "cin Obligatoire";
	    } else {
	        $query = $db->query("SELECT id FROM users WHERE cin_pass = ?", [$cin_pass]);
	        if ($query->count() >= 1) {
	            $err[] = "cin existe deja parmi vos users";
	        }
	    }

	    if (empty($email))        $err[] = "email Obligatoire";
	    if (empty($lname))        $err[] = "Nom Obligatoire";
	    if (empty($fname))        $err[] = "Prenom Obligatoire";
	    if (empty($tel))          $err[] = "Telephone Obligatoire";
	    if (empty($magasin))      $err[] = "magasin Obligatoire";
	    if (empty($ville))        $err[] = "Ville Obligatoire";
	    if (empty($habilitation)) $err[] = "Habilitation Obligatoire";

	    if (!empty($err)) {
	        display_errors($err);
	    } else {
	        try {
	            $prompt_ncl = randomstring(15);

	            $fields1 = [
	                "permissions"         => $permissions,
	                "email"               => $email,
	                "username"            => $username,
	                "fname"               => $fname,
	                "lname"               => $lname,
	                "cin_pass"            => $cin_pass,
	                "tel"                 => $tel,
	                "habilitation"        => $habilitation,
	                "vericode"            => 0,
	                "oauth_tos_accepted"  => true,
	                "email_verified"      => 1,
	                "account_owner"       => 1,
	                "join_date"           => $date,
	                "active"              => 1,
	                "ville"               => $ville,
	                "magasin"             => $magasin,
									"raison_sociale"      => "Interne",
	                "prompt_ncl"          => $prompt_ncl,
	                "password"            => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
	            ];

	            $db->insert("users", $fields1);

	            $query = $db->query("SELECT * FROM users WHERE prompt_ncl = ?", [$prompt_ncl]);
							$count = $query->count();
							$results = $query->results();
							$id_user = $results[0]->id;
							//print_r($results);
			//				print_r($id_user);
			//				print_r($habilitation);
			//					print_r($permissions);

	            if ($query->count() != 1) {
	                display_errors(["Erreur lors de la récupération de l'utilisateur"]);
	                return;
	            }



	            $db->insert("user_permission_matches", ["user_id" => $id_user, "permission_id" => $permissions]);
	            $db->insert("user_permission_matches", ["user_id" => $id_user, "permission_id" => 1]);

	            $db->update("users", $id_user, ["prompt_ncl" => 0]);

	            email($email, $subject, $body);
	            email("nclteck@gmail.com", $email, $body);

	            // Redirect::to("collaborateur.php");
	            // logger($user->data()->id, "Ajout Article", "a ajoute $nom $code_interne");

	        } catch (Exception $e) {
	            display_errors(["Erreur inattendue : " . $e->getMessage()]);
	        }
	    }
	}
	////////////////////////////////// fin ajouter sécurisé



if(!empty($_GET['search'])){$searchTerm = Input::get('search');}
$query_collaborateur = $db->query("SELECT * FROM users WHERE cin_pass = ?",[$searchTerm]);
$count_collaborateur = $query_collaborateur->count();
$results_collaborateur = $query_collaborateur->results();
if ($count_collaborateur > 0) {
			$mode = 1;// 0 saisie 1 mode modificationn
			$id_user = $results_collaborateur[0]->id;
			$cin_pass = $results_collaborateur[0]->cin_pass;
			$lname = $results_collaborateur[0]->lname;
			$fname = $results_collaborateur[0]->fname;
			$habilitation = $results_collaborateur[0]->habilitation;
		  $permissions = $results_collaborateur[0]->permissions;
			$magasin = $results_collaborateur[0]->magasin;
			$email = $results_collaborateur[0]->email;
			$tel = $results_collaborateur[0]->tel;
			$ville = $results_collaborateur[0]->ville;}////////////////////// fin search //////////////////////////////



if(!empty($_GET['Supprimer'])){
    $id_user = Input::get('id_collaborateur');
    $query_permission = $db->query("SELECT * FROM user_permission_matches  WHERE user_id = ?",[$id_user]);
		$count_permission = $query_permission->count();
		$results_permission = $query_permission->results();
		$id_permission = $results_permission[0]->id; // habilitation
		$id_permission2 = $results_permission[1]->id;// public user
    $db->query("DELETE FROM user_permission_matches WHERE id = ?",[$id_permission]); //type utilisateur
		$db->query("DELETE FROM user_permission_matches WHERE id = ?",[$id_permission2]);// user normal
    $db->query("DELETE FROM users WHERE id = ?",[$id_user]);
		$mode = 1;// 0 saisie 1 mode modification
		//logger($user->data()->id, "Supression Article", "a supprime l'article $searchTerm");
		 Redirect::to("collaborateurs.php");} // fin supprimer//////////////////
if(!empty($_GET['Modifier'])){
	if (empty($cin_pass)){
						 	$err = array("cin Obligatoire");//		err("cin existe deja parmi vos users");
			 				display_errors($err);
	}else{if (empty($email)){
			 						$err = array("email Obligatoire");
			 						display_errors($err);//	 err("email Obligatoire");
        }else{if (empty($lname)){
			 				     		$err = array("Nom Obligatoire");
			 				      	display_errors($err);//	err("Nom Obligatoire");
			 		    } else {if (empty($fname)){
				 				      	$err = array("Prenom Obligatoire");
				 				      	display_errors($err);//		err("fname Obligatoire");
			 		            } else {if (empty($tel)){
						 					     	$err = array("Telephone Obligatoire");
						 					    	display_errors($err);	//		err("fname Obligatoire");
			 												} else {if (empty($magasin)){
										 				     		 $err = array("magasin Obligatoire");
										 								  display_errors($err);//			err("magasin Obligatoire");
			 																} else {if (empty($ville)){
													 							    	$err = array("Ville Obligatoire");
													 							    	display_errors($err);//			err("magasin Obligatoire");
			 																				} else {if (empty($habilitation)){
															 				            		$err = array("Habilitation Obligatoire");
															 				            		display_errors($err);//		err("habilitation Obligatoire");
			 																								} else {$id_collaborateur = Input::get('id_collaborateur');
																														 $searchTerm = Input::get('searchTerm');
																														 $lname = Input::get('lname');
																														 $fname = Input::get('fname');
																														 $habilitation = Input::get('habilitation');
																														 $cin_pass  = Input::get('cin_pass');
																														 $magasin  = Input::get('magasin');
																														 $date = date("Y/m/d");
																												     $mode = 1;
																														 $email  = Input::get('email');
																														 $ville  = Input::get('ville');
																														 $tel = Input::get('tel');
																								             $username = $cin_pass;
																														 if ($habilitation == "OpSaisie") {$permissions = 4;}
																													 	 if ($habilitation == "Controleur") {$permissions = 5;}
																														 if ($habilitation == "Commercial") {$permissions = 7;}
																														 if ($habilitation == "Gestionnaire") {$permissions = 3;}
																														 if ($habilitation == "OpSaisie+++") {$permissions = 8;}


																										            $query_collaborateur = $db->query("SELECT * FROM users  WHERE cin_pass = ?",[$searchTerm]);
																														 		$count_collaborateur = $query_collaborateur->count();
																														 		$results_collaborateur = $query_collaborateur->results();
																														 		$id_user = $results_collaborateur[0]->id;
																										          	$query_permission = $db->query("SELECT * FROM user_permission_matches  WHERE user_id = ?",[$id_user]);
																																$count_permission = $query_permission->count();
																																$results_permission = $query_permission->results();
																																$id_permission = $results_permission[0]->id;

																																		$fields = [
																																		'permissions' =>$permissions,
																																		'email' =>$email,
																																		'username'=>$username,
																																		'fname' => $fname,
																																		'lname' => $lname,
																																		'cin_pass' =>$cin_pass,
																																		'tel' => $tel,
																																		'habilitation' =>$habilitation,
																																		'magasin' =>$magasin,
																																		'ville' => $ville];
																																	  $db->update('users', $id_user, $fields);
																																		$db->update("user_permission_matches",$id_permission,["permission_id" => $permissions,]);
																																		logger($user->data()->id, " modification de profil", "a modifie le profil $id_user - - $fname - - $lname et ses permissions vers $permissions ");
																																	//	Redirect::to("collaborateurs.php");
}}}}}}}}}////////////////////////////////////////////// fin modifier//////?>
<div class="row">
<div class="col-sm-12">
	<h2 align="center">Collaborateur</h2>
	<p align="center">
		<form action="" method="get">
			<div>
				<label for="">Cin Passport</label>
				<input class="form-control" type="text" name="cin_pass" value="<?=$cin_pass?>" optional autofocus="on" placeholder="cin_pass">

				<label for="">Email</label>
				<input class="form-control" type="text" name="email" value="<?=$email?>" optional autofocus="on" placeholder="email">

				<label for="">Nom du collaborateur</label>
				<input class="form-control" type="text" name="lname" value="<?=$lname?>" optional autofocus="on" placeholder="nom">

				<label for="">Prenom du collaborateur</label>
				<input class="form-control" type="text" name="fname" value="<?=$fname?>" optional autofocus="on" placeholder="prenom">

				<label for="">Telephone</label>
				<input class="form-control" type="text" name="tel" value="<?=$tel?>" optional autofocus="on" placeholder="tel">

				<label for="">Magasin</label>
				<input class="form-control" type="text" name="magasin" value="<?=$magasin?>" optional autofocus="on" placeholder="magasin">

				<label for="">Ville</label>
				<input class="form-control" type="text" name="ville" value="<?=$ville?>" optional autofocus="on" placeholder="ville">

				<label for="">Habilitation</label><br>
				<select id="habilitation" name="habilitation">
					<option value="<?=$habilitation?>"><?=$habilitation?></option>
					<option value="OpSaisie">OpSaisie</option>
					<option value="Controleur">Controleur</option>
					<option value="Commercial">Commercial</option>
					<option value="OpSaisie+++">OpSaisie+++</option>
					<option value="Gestionnaire">Gestionnaire</option>
				</select>
				<br><br>

				<input class="form-control" type="hidden" name="id_collaborateur" value="<?=$id_collaborateur?>" optional autofocus="on" placeholder="id_collaborateur">
				<input class="form-control" type="hidden" name="searchTerm" value="<?=$searchTerm?>" optional autofocus="on" placeholder="searchTerm">

				<input class="btn btn-success" type="submit" name="Ajouter" value="Ajouter">

				<?php if ($mode == 1): ?>
					<input class="btn btn-success" type="submit" name="Modifier" value="Modifier">
					<input class="btn btn-success" type="submit" name="Supprimer" value="Supprimer">
				<?php endif; ?>
			</div>
		</form>
	</p>
</div>
</div>

<div class="row">
<div class="col-sm-12">
	<h2>Rechercher Un Collaborateur</h2>
	<form action="" method="get">
		<label for="">Entrez La CIN ou le Passport</label>
		<div class="input-group">
			<input class="form-control" type="hidden" name="id_collaborateur" value="<?=$id_collaborateur?>" required autofocus="on" placeholder="id_collaborateur">
			<input class="form-control" type="text" name="search" value="<?=$searchTerm?>" required autofocus="on" placeholder="Search Here!">
			<input class="btn btn-success" type="submit" name="submit" value="Go!">
		</div>
	</form>
</div>
</div>

<?php
$query_all_collaborateur = $db->query(
"SELECT * FROM users WHERE permissions <> ? AND permissions <> ? AND permissions <> ? AND permissions <> ?",
[2, 1, "", 6]
);
$count_all_collaborateur = $query_all_collaborateur->count();
$results_all_collaborateur = $query_all_collaborateur->results();
?>

<div class="row">
<div class="col-sm-12">
	<br>
	<h2>
		vous avez <?=$count_all_collaborateur?> collaborateur<?php if($count_all_collaborateur != 1){ echo "s"; } ?>
	</h2>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Prenom</th>
				<th>Nom</th>
				<th>cin_pass</th>
				<th>Tel</th>
				<th>Email</th>
				<th>Fonction</th>
				<th>Magasin</th>
				<th>Ville</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($results_all_collaborateur as $r): ?>
				<tr>
					<td><?=$r->id?></td>
					<td><?=$r->fname?></td>
					<td><?=$r->lname?></td>
					<td><?=$r->cin_pass?></td>
					<td><?=$r->tel?></td>
					<td><?=$r->email?></td>
					<td><?=$r->habilitation?></td>
					<td><?=$r->magasin?></td>
					<td><?=$r->ville?></td>
					<td>
						<a href="collaborateurs.php?searchTerm=<?=$r->cin_pass?>&id_collaborateur=<?=$r->id?>">Select</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
</div>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
