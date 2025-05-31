<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }
?>

<?php
////////////////////////////////////////search /////////////////////////////////////////////////////////////

$mode = Input::get('mode');
$id_sup = Input::get('id_sup');
$searchTerm = Input::get('search');
$id_client_revendeur = $user->data()->id;
	if(!empty($_GET['search'])){


  $query = $db->query("SELECT * FROM commandes  WHERE id = ? and id_client_revendeur = ?",[$searchTerm,$id_client_revendeur]);
	$count = $query->count();
	$results = $query->results();


}else{
	$query = $db->query("SELECT * FROM commandes  WHERE status_commande = ? and id_client_revendeur = ?",['En Attente de traitement',$id_client_revendeur]);
	$count = $query->count();
	$results = $query->results();

}


if($mode==1){
$db->query("DELETE FROM commandes WHERE id = ?",[$id_sup]);
Redirect::to("commandes_suivi.php");

}

?>

			<div class="row">
				<!-- left col -->

				<!-- right col -->
				<div  class="col-sm-12">

					<h1 style="color:red;">Suivi des commandes </h1>
					<h3>Rechercher Une commande </h3>
					<form class="" action="" method="get">
						<label for="">Entez La Numero De Commande</label>
						<div class="input-group">

						<input class="form-control" type="text" name="search" value="<?=$searchTerm?>"autofocus="on" placeholder="Search Here!">
						<input class="btn btn-success" type="submit" name="submit" value="Go!">

						</div>
					</form>
				</div>
			</div>
			<?php

/*	 */?>

<?php



//print_r($results_all_client);
					?>


				<div class="row">
					<div class="col-sm-12">
						<br>
						<h2>vous avez <?=$count?> Commande<?php if($count != 1){ echo "s";}	?></h2>

						<table class="table table-striped">
							<thead>

								<tr>

									<th>Numero</th>
									<th>Date</th>
									<th>Revendeur</th>
									<th>Nom client</th>
									<th>Prenom client</th>
									<th>marque</th>
									<th>modele</th>
									<th>version</th>
									<th>couleur</th>
									<th>entrepot</th>
									<th>Status</th>
									<th></th>

								</tr>
							</thead>
							<tbody>
								<?php foreach ($results as $r) { ?>
									<tr>
										<td><?=$r->id?></td>
										<td><?=$r->date_commande?></td>
										<td><?=$r->Nom_revendeur?></td>
										<td><?=$r->nom_acheteur?></td>
										<td><?=$r->prenom_acheteur?></td>
										<td><?=$r->marque?></td>
										<td><?=$r->modele?></td>
										<td><?=$r->version?></td>
										<td><?=$r->couleur?></td>
										<td><?=$r->entrepot?></td>
										<td><?=$r->status_commande?></td>


										<td>
											<a href="commandes_suivi.php?mode=1&id_sup=<?=$r->id?>">Annuler</a>  <!--" Suprimer L'affectation "-->
										</td>

									</tr>
								<?php } ?>
							</tbody>
						</table>

					</div>
				</div>



<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
