<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }

// Récupération des paramètres GET
$mode = 0;// 0 saisie 1 mode modification
$searchTerm1_categorie = Input::get('searchTerm1_categorie');
$searchTerm2_marque = Input::get('searchTerm2_marque');
$searchTerm3_modele= Input::get('searchTerm3_modele');
$searchTerm4_version = Input::get('searchTerm4_version');
$searchTerm5_couleur = Input::get('searchTerm5_couleur');
$searchTerm6_vin = Input::get('searchTerm6_vin');
$entrepot = Input::get('entrepot');
$disponibilite = Input::get('disponibilite');
$id_client = $user->data()->id;
$search_vin = Input::get('search_vin');
//$id_produit = Input::get('id_produit');
//print_r($id_produit);
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = Input::get('action');
    $categorie = Input::get('categorie');
    $marque = Input::get('marque');
    $modele = Input::get('modele');
    $version = Input::get('version');
    $couleur = Input::get('couleur');
    $vin = Input::get('vin');
		$entrepot = Input::get('entrepot');
    $disponibilite = Input::get('disponibilite');
    $id_produit = Input::get('id_produit');

    // Ajouter un produit



   if ($action == 'ajouter') {
    /*     $db->query("INSERT INTO produits (categorie, marque, modele, version, couleur, vin, entrepot) VALUES (?, ?, ?, ?, ?, ?, ?)", [
            $categorie, $marque, $modele, $version, $couleur, $vin , $entrepot
        ]);*/

				$query_vin = $db->query("SELECT * FROM produits WHERE vin = ?", [$vin]);
				$count_vin = $query_vin->count();

					if($count_vin > 0){
						$err = array("VIN existe deja parmi vos Produits A");
							display_errors($err);

				} else {
					$fields_ajouter = array(

						"categorie"=>$categorie,
						"marque"=>$marque,
						"modele"=>$modele,
						"version"=>$version,
						"couleur"=>$couleur,
						"vin"=>$vin,
						"entrepot"=>$entrepot,
            "disponibilite"=>$disponibilite,
									);


					$db->insert("produits",	$fields_ajouter );
				}

    // Modifier un produit
    }elseif ($action == 'modifier' && !empty($entrepot)) {


															$query_id_product = $db->query("SELECT id FROM produits WHERE vin = ?", [$searchTerm6_vin]);
															$count_id_product = $query_id_product->count();
															$results_id_product = $query_id_product->results();


															if($count_id_product <= 0){ // cas impossible c est un ajout

																$fields_modifier = array(
																	"categorie"=>$categorie,
																	"marque"=>$marque,
																	"modele"=>$modele,
																	"version"=>$version,
																	"couleur"=>$couleur,
																	"vin"=>$vin,
																	"entrepot"=>$entrepot,
                                  "disponibilite"=>$disponibilite,
																);

																	$db->update("produits",$id_produit,$fields_modifier);

                              }else{
																$id_produit = $results_id_product[0]->id;
																$query_vin_nbr = $db->query("SELECT * FROM produits WHERE vin = ? and id <> ?", [$vin,$id_produit]);
																$count_vin_nbr = $query_vin_nbr->count();
																$results_vin_nbr = $query_vin_nbr->results();
//print_r("vin exist");
//print_r($results_vin_nbr);
																					if($count_vin_nbr > 0){
																						$err = array("VIN existe deja parmi vos Produits");
																							display_errors($err);

																				  }else{

																						$fields_modifier = array(
																							"categorie"=>$categorie,
																							"marque"=>$marque,
																							"modele"=>$modele,
																							"version"=>$version,
																							"couleur"=>$couleur,
																							"vin"=>$vin,
																							"entrepot"=>$entrepot,
                                              "disponibilite"=>$disponibilite,
																						);

																							$db->update("produits",$id_produit,$fields_modifier);

            															}

														  }


    //   $db->query("UPDATE produits SET categorie = ?, marque = ?, modele = ?, version = ?, couleur = ?, entrepot = ? vin =? WHERE vin  = ?", [
    //      $categorie, $marque, $modele, $version, $couleur, $entrepot, $vin
      //  ]);

    // Supprimer un produit
    }elseif ($action == 'supprimer' && !empty($vin)) {
        $db->query("DELETE FROM produits WHERE vin = ?", [$vin]);
    }
    $searchTerm1_categorie = $categorie;
    $searchTerm2_marque = $marque;
    $searchTerm3_modele= $modele;
    $searchTerm4_version = $version;
    $searchTerm5_couleur = $couleur;
    $searchTerm6_vin = $vin;
    $entrepot = $entrepot;
    $disponibilite = $disponibilite;
    $id_client = $user->data()->id;
}
// search
if(!empty($_GET['search'])){
  $search_vin = Input::get('search');


$query_search_vin = $db->query("SELECT * FROM produits WHERE vin = ?",[$search_vin]);
$count_search_vin = $query_search_vin->count();
$results_search_vin = $query_search_vin->results();




//logger($user->data()->id, "Search article", "Searched for $searchTerm");

if ($count_search_vin > 0) {


$id_produit = $results_search_vin[0]->id;
$searchTerm1_categorie = $results_search_vin[0]->categorie;
$searchTerm2_marque = $results_search_vin[0]->marque;
$searchTerm3_modele = $results_search_vin[0]->modele;
$searchTerm4_version = $results_search_vin[0]->version;
$searchTerm5_couleur = $results_search_vin[0]->couleur;
$searchTerm6_vin = $results_search_vin[0]->vin;
$entrepot = $results_search_vin[0]->entrepot;
$disponibilite = $results_search_vin[0]->disponibilite;
$id_client = $user->data()->id;

}else {
  $err = array("VIN n'existe pas");
    display_errors($err);
}
}

// Récupération des catégories
$query = $db->query("SELECT DISTINCT categorie FROM produits", []);
$count = $query->count();
$results = $query->results();
?>

<?php if ($count > 0) { ?>
	<div class="row">
    <h2> <p style="color:red;"><?php echo "Produits Acces Modification";?> </h6>

		<div class="col-md-9">
		<h6> <?php echo "$searchTerm1_categorie";?> <?php echo "$searchTerm2_marque";?>  <?php echo "$searchTerm3_modele";?>   <?php echo "$searchTerm4_version";?>     <?php echo "$searchTerm5_couleur"?>     <?php echo "$searchTerm6_vin";?></h6>

    <div class="container-fluid">
        <div class="row g-0"> <!-- Ajout de g-0 pour supprimer les espaces entre les colonnes -->

            <!-- Colonne des Catégories -->
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th><?=$count?> Catégorie<?php if ($count != 1) echo "s"; ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $r) { ?>
                            <tr>
                                <td style="background-color: <?= ($searchTerm1_categorie == $r->categorie) ? '#ADD8e6' : '' ?>">
                                    <a href="produits.php?searchTerm1_categorie=<?= urlencode($r->categorie) ?>">
                                        <?= htmlspecialchars($r->categorie) ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Colonne des Marques (si une catégorie est sélectionnée) -->
            <?php if (!empty($searchTerm1_categorie)) {
                $query2 = $db->query("SELECT DISTINCT marque FROM produits WHERE categorie = ?", [$searchTerm1_categorie]);
                $count2 = $query2->count();
                $results2 = $query2->results();
            ?>
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th><?=$count2?> Marque<?php if ($count2 != 1) echo "s"; ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results2 as $r2) { ?>
                            <tr>
                                <td style="background-color: <?= ($searchTerm2_marque == $r2->marque) ? '#ADD8e6' : '' ?>">
                                    <a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($r2->marque) ?>">
                                        <?= htmlspecialchars($r2->marque) ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <!-- Colonne des Modèles (si une marque est sélectionnée) -->
            <?php if (!empty($searchTerm2_marque)) {
                $query3 = $db->query("SELECT DISTINCT modele FROM produits WHERE categorie = ? and marque = ?", [$searchTerm1_categorie,$searchTerm2_marque]);
                $count3 = $query3->count();
                $results3 = $query3->results();
            ?>
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th><?=$count3?> Modèle<?php if ($count3 != 1) echo "s"; ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results3 as $r3) { ?>
                            <tr>
                                <td style="background-color: <?= ($searchTerm3_modele== $r3->modele) ? '#ADD8e6' : '' ?>">
                                    <a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($r3->modele) ?>">
                                        <?= htmlspecialchars($r3->modele) ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <!-- Colonne des Versions (si un modèle est sélectionné) -->
            <?php if (!empty($searchTerm3_modele)) {
                $query4 = $db->query("SELECT DISTINCT version FROM produits WHERE categorie = ? and marque = ? and modele= ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele]);
                $count4 = $query4->count();
                $results4 = $query4->results();
            ?>
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th><?=$count4?> Version<?php if ($count4 != 1) echo "s"; ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results4 as $r4) { ?>
                            <tr>
                                <td style="background-color: <?= ($searchTerm4_version == $r4->version) ? '#ADD8e6' : '' ?>">
                                    <a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($r4->version) ?>">
                                        <?= htmlspecialchars($r4->version) ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <!-- Colonne des Couleurs (si une version est sélectionnée) -->
            <?php if (!empty($searchTerm4_version)) {
                $query5 = $db->query("SELECT DISTINCT couleur FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version]);
                $count5 = $query5->count();
                $results5 = $query5->results();
            ?>
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th><?=$count5?> Couleur<?php if ($count5 != 1) echo "s"; ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results5 as $r5) { ?>
                            <tr>
                                <td style="background-color: <?= ($searchTerm5_couleur == $r5->couleur) ? '#ADD8e6' : '' ?>">
                                    <a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($r5->couleur) ?>">
                                        <?= htmlspecialchars($r5->couleur) ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <!-- Colonne des VIN (si une couleur est sélectionnée) -->
            <?php if (!empty($searchTerm5_couleur)) {
                $query6 = $db->query("SELECT * FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ? and couleur = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur]);
              //  $query6 = $db->query("SELECT DISTINCT vin FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ? and couleur = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur]);
                $count6 = $query6->count();
                $results6 = $query6->results();

            ?>
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
													<th><?=$count6?> VIN <?php if ($count6 != 1) echo "s"; ?></th>
												  <th>Entrepot</th>
                          <th>Disponibilite</th>
												</tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results6 as $r6) {  ?>
													<?php $id_client=$r6->id?>
                            <tr>

                                <td style="background-color: <?= ($searchTerm6_vin == $r6->vin) ? '#ADD8e6' : '' ?>">
                                    <a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($searchTerm5_couleur) ?>&searchTerm6_vin=<?= urlencode($r6->vin)?>&entrepot=<?= urlencode($r6->entrepot) ?>&disponibilite=<?= urlencode($r6->disponibilite)?>&id_produit=<?= urlencode($r6->id) ?>">
                                        <?= htmlspecialchars($r6->vin) ?>
                                    </a>
																</td>

																<td style="background-color: <?= ($entrepot == $r6->entrepot) ? '' : '' ?>">
																	<?php /*<a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($searchTerm5_couleur) ?>&searchTerm6_vin=<?= urlencode($r6->vin)?>&entrepot=<?= urlencode($r6->entrepot) ?>">  */?>
																			<?= htmlspecialchars($r6->entrepot) ?>
																	</a>
																</td>

                                <td style="background-color: <?= ($disponibilite == $r6->disponibilite) ? '' : '' ?>">
                                  <?php /*<a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($searchTerm5_couleur) ?>&searchTerm6_vin=<?= urlencode($r6->vin)?>&entrepot=<?= urlencode($r6->entrepot) ?>">  */?>
                                      <?= htmlspecialchars($r6->disponibilite) ?>
                                  </a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
<?php /* ?>
						<!-- Colonne des ENTREPOT (si VIN est sélectionnée) -->
						<?php if (!empty($searchTerm6_vin)) {

							  $query7= $db->query("SELECT DISTINCT entrepot FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ? and couleur = ? and vin = ?",[$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur,$searchTerm6_vin]);
								$count7 = $query7->count();
								$results7 = $query7->results();
						?>
						<div class="col-auto">
								<table class="table table-striped table-bordered">
										<thead>
												<tr><th><?=$count7?> ENTREPOT <?php if ($count7 != 1) echo "s"; ?></th></tr>
										</thead>
										<tbody>
												<?php foreach ($results7 as $r7) { ?>
														<tr>
																<td style="background-color: <?= ($entrepot == $r7->entrepot) ? '#ADD8e6' : '' ?>">
																	<a href="produits.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($searchTerm5_couleur) ?>&searchTerm6_vin=<?= urlencode($r6->vin) ?>&entrepot=<?= urlencode($r7->entrepot) ?>">
																			<?= htmlspecialchars($r7->entrepot) ?>
																	</a>
																</td>
														</tr>
												<?php } ?>
										</tbody>
								</table>
						</div>
						<?php } ?>
						*/?>
      </div>
        </div>
</div>
				<!-- FORMULAIRE D'AJOUT / MODIFICATION -->
        <div class="col-md-3">
		    <form method="post" action="produits.php">
		        <input type="hidden" name="mode" value="<?= $mode ?>">
		        <input type="hidden" name="id_client" value="<?= $id_client ?>">
						<input type="hidden" name="id_produit" value="<?=$id_produit?>">
						<input type="hidden" name="searchTerm1_categorie" value="<?=$searchTerm1_categorie ?>">
            <input type="hidden" name="searchTerm2_marque" value="<?=$searchTerm2_marque ?>">
						<input type="hidden" name="searchTerm3_modele" value="<?=$searchTerm3_modele ?>">
						<input type="hidden" name="searchTerm4_version" value="<?=$searchTerm4_version ?>">
						<input type="hidden" name="searchTerm5_couleur" value="<?=$searchTerm5_couleur ?>">
						<input type="hidden" name="searchTerm6_vin" value="<?=$searchTerm6_vin ?>">
            <input type="hidden" name="entrepot" value="<?=$entrepot ?>">
            <input type="hidden" name="Disponibilite" value="<?=$Disponibilite ?>">



		            <div class="col-auto">
		                <label>Catégorie</label>
		                <input type="text" name="categorie" class="form-control" value="<?= $searchTerm1_categorie ?>" required>
		            </div>
		            <div class="col-auto">
		                <label>Marque</label>
		                <input type="text" name="marque" class="form-control" value="<?= $searchTerm2_marque ?>" required>
		            </div>
		            <div class="col-auto">
		                <label>Modèle</label>
		                <input type="text" name="modele" class="form-control" value="<?= $searchTerm3_modele ?>" required>
		            </div>
		            <div class="col-auto">
		                <label>Version</label>
		                <input type="text" name="version" class="form-control" value="<?= $searchTerm4_version ?>" required>
		            </div>
		            <div class="col-auto">
		                <label>Couleur</label>
		                <input type="text" name="couleur" class="form-control" value="<?= $searchTerm5_couleur ?>" required>
		            </div>
		            <div class="col-auto">
		                <label>VIN</label>
		                <input type="text" name="vin" class="form-control" value="<?= $searchTerm6_vin ?>" required>
		            </div>
								<div class="col-auto">
										<label>Entrepot</label>
										<input type="text" name="entrepot" class="form-control" value="<?= $entrepot ?>" required>
								</div>
                <div class="col-auto">
                    <label>Disponibilite</label>
                    <input type="text" name="disponibilite" class="form-control" value="<?= $disponibilite ?>">
                </div>


		        <br>
		        <button type="submit" name="action" value="ajouter" class="btn btn-primary">Ajouter</button>
		        <button type="submit" name="action" value="modifier" class="btn btn-warning" <?= empty($r6->vin) ? 'disabled' : '' ?>>Modifier</button>
		        <button type="submit" name="action" value="supprimer" class="btn btn-danger" <?= empty($r6->vin) ? 'disabled' : '' ?>>Supprimer</button>
		    </form>



        <h2>Rechercher Un VIN</h2>
        <form class="" action="" method="get">
          <label for="">Entez Le VIN</label>
          <div class="input-group">
          <input class="form-control" type="text" name="search" value="<?=$search_vin?>"required autofocus="on" placeholder="Search Here!">
          <input class="btn btn-success" type="submit" name="submit" value="Go!">

          </div>
        </form>



    </div>
    </div>

<?php } ?>







<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
