<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
//if (!securePage($_SERVER['PHP_SELF'])) { die(); }

// Récupération des paramètres GET
$mode = 0;// 0 saisie 1 mode modification
//$cin_pass_recto_acheteur = "";
$id_client_revendeur = $user->data()->id;
$revendeur = $user->data()->fname;

$searchTerm1_categorie = Input::get('searchTerm1_categorie');
$searchTerm2_marque = Input::get('searchTerm2_marque');
$searchTerm3_modele= Input::get('searchTerm3_modele');
$searchTerm4_version = Input::get('searchTerm4_version');
$searchTerm5_couleur = Input::get('searchTerm5_couleur');
$searchTerm6_vin = Input::get('searchTerm6_vin');
$entrepot = Input::get('entrepot');

$nom_acheteur = Input::get('nom_acheteur');
$prenom_acheteur = Input::get('prenom_acheteur');
$tel_acheteur = Input::get('tel_acheteur');
$ville_acheteur = Input::get('ville_acheteur');
$cin_pass_recto_acheteur = Input::get('cin_pass_recto_acheteur');
$cin_pass_verso_acheteur = Input::get('cin_pass_verso_acheteur');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = Input::get('action');
    $categorie = Input::get('categorie');
    $marque = Input::get('marque');
    $modele = Input::get('modele');
    $version = Input::get('version');
    $couleur = Input::get('couleur');
    $vin = Input::get('vin');
		$entrepot = Input::get('entrepot');

    $nom_acheteur = Input::get('nom_acheteur');
    $prenom_acheteur = Input::get('prenom_acheteur');
    $tel_acheteur = Input::get('tel_acheteur');
    $ville_acheteur = Input::get('ville_acheteur');
    $cin_pass_recto_acheteur = Input::get('cin_pass_recto_acheteur');
    $cin_pass_verso_acheteur = Input::get('cin_pass_verso_acheteur');


    // Ajouter un produit



   if ($action == 'commander') {
    /*     $db->query("INSERT INTO produits (categorie, marque, modele, version, couleur, vin, entrepot) VALUES (?, ?, ?, ?, ?, ?, ?)", [
            $categorie, $marque, $modele, $version, $couleur, $vin , $entrepot
        ]);*/

			//	$query_vin = $db->query("SELECT * FROM produits WHERE vin = ?", [$vin]);
			//	$count_vin = $query_vin->count();


					$fields_ajouter = array(

						"categorie"=>$categorie,
						"marque"=>$marque,
						"modele"=>$modele,
						"version"=>$version,
						"couleur"=>$couleur,
					//	"vin"=>$vin,
						"entrepot"=>$entrepot,

            "revendeur"=>$revendeur,
            "id_client_revendeur"=>$id_client_revendeur,
          	"nom_acheteur"=>$nom_acheteur,
            "prenom_acheteur"=>$prenom_acheteur,
            "tel_acheteur"=>$tel_acheteur,
            "ville_acheteur"=>$ville_acheteur,
            "cin_pass_recto_acheteur"=>$cin_pass_recto_acheteur,
            "cin_pass_verso_acheteur"=>$cin_pass_verso_acheteur,


									);

					$db->insert("commandes",	$fields_ajouter );
    }




    $searchTerm1_categorie = $categorie;
    $searchTerm2_marque = $marque;
    $searchTerm3_modele= $modele;
    $searchTerm4_version = $version;
    $searchTerm5_couleur = $couleur;
    $searchTerm6_vin = $vin;
    $entrepot = $entrepot;
    $id_client_revendeur = $user->data()->id;
}


// Récupération des catégories
$query = $db->query("SELECT DISTINCT categorie FROM produits", []);
$count = $query->count();
$results = $query->results();
?>

<?php if ($count > 0) { ?>
	<div class="row">
    <h2> <p style="color:red;"><?php echo "Espace Commandes";?> </h6>

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
                                    <a href="commandes.php?searchTerm1_categorie=<?= urlencode($r->categorie) ?>">
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
                                    <a href="commandes.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($r2->marque) ?>">
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
                                    <a href="commandes.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($r3->modele) ?>">
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
                                    <a href="commandes.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($r4->version) ?>">
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
                                    <a href="commandes.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($r5->couleur) ?>">
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

              $query7 = $db->query("SELECT DISTINCT entrepot FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ?and couleur = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur]);
              $count7 = $query7->count();
              $results7 = $query7->results();




          //      $query6 = $db->query("SELECT * FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ? and couleur = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur]);
              //  $query6 = $db->query("SELECT DISTINCT vin FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ? and couleur = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur]);
          //      $count6 = $query6->count();
          //      $results6 = $query6->results();

            ?>
            <div class="col-auto">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                          <th>Disponibilite</th>


												</tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results7 as $r7) {

                          ?>


                            <tr>

                              <td style="background-color: <?= ($entrepot == $r7->entrepot) ? '#ADD8e6' : '' ?>">
                                    <a href="commandes.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($searchTerm5_couleur) ?>&entrepot=<?= urlencode($r7->entrepot) ?>">
                                    <?= htmlspecialchars($r7->entrepot) ?>
                                </a>
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
																	<a href="commandes.php?searchTerm1_categorie=<?= urlencode($searchTerm1_categorie) ?>&searchTerm2_marque=<?= urlencode($searchTerm2_marque) ?>&searchTerm3_modele=<?= urlencode($searchTerm3_modele) ?>&searchTerm4_version=<?= urlencode($searchTerm4_version) ?>&searchTerm5_couleur=<?= urlencode($searchTerm5_couleur) ?>&searchTerm6_vin=<?= urlencode($r6->vin) ?>&entrepot=<?= urlencode($r7->entrepot) ?>">
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
<?php


$fild = Input::get('fild');
$fileName = Input::get('file');
$targetDir = "uploads/";
$targetFilePath = $targetDir .$fild ."_".$fileName ;

$file = $fild ."_".$fileName;
$dir = 'uploads/';




if(isset($_GET[$file]) && file_exists($dir . $file)) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $type = $finfo->file($file); // get MIME-Type
    header("Content-Type: " . $type);
    header("Content-Disposition: attachment; filename=\"" . $file . "\"");
    readfile($dir . $file);
}
 ?>







      </div>
        </div>
</div>
				<!-- FORMULAIRE D'AJOUT / MODIFICATION -->
        <div class="col-md-3">
		    <form method="post" action="upload.php">
		        <input type="hidden" name="mode" value="<?= $mode ?>">
		        <input type="hidden" name="id_client_revendeur" value="<?= $id_client_revendeur ?>">
						<input type="hidden" name="id_produit" value="<?=$id_produit?>">
						<input type="hidden" name="searchTerm1_categorie" value="<?=$searchTerm1_categorie ?>">
            <input type="hidden" name="searchTerm2_marque" value="<?=$searchTerm2_marque ?>">
						<input type="hidden" name="searchTerm3_modele" value="<?=$searchTerm3_modele ?>">
						<input type="hidden" name="searchTerm4_version" value="<?=$searchTerm4_version ?>">
						<input type="hidden" name="searchTerm5_couleur" value="<?=$searchTerm5_couleur ?>">
						<input type="hidden" name="nom_acheteur" value="<?=$nom_acheteur ?>">



		            <div class="col-auto">
		                <label>Catégorie</label>
		                <input type="text" name="categorie" class="form-control" value="<?= $searchTerm1_categorie ?>" required disabled>
		            </div>
		            <div class="col-auto">
		                <label>Marque</label>
		                <input type="text" name="marque" class="form-control" value="<?= $searchTerm2_marque ?>" required disabled>
		            </div>
		            <div class="col-auto">
		                <label>Modèle</label>
		                <input type="text" name="modele" class="form-control" value="<?= $searchTerm3_modele ?>" required disabled>
		            </div>
		            <div class="col-auto">
		                <label>Version</label>
		                <input type="text" name="version" class="form-control" value="<?= $searchTerm4_version ?>" required disabled>
		            </div>
		            <div class="col-auto">
		                <label>Couleur</label>
		                <input type="text" name="couleur" class="form-control" value="<?= $searchTerm5_couleur ?>" required disabled>
		            </div>

								<div class="col-auto">
										<label>Entrepot</label>
										<input type="text" name="entrepot" class="form-control" value="<?= $entrepot ?>" required disabled>
								</div>

                <div class="col-auto">
		                <label>Nom Client</label>
		                <input type="text" name="nom_acheteur" class="form-control" value="<?= $nom_acheteur ?>" required>
		            </div>

                <div class="col-auto">
                    <label>Prenom Client</label>
                    <input type="text" name="prenom_acheteur" class="form-control" value="<?= $prenom_acheteur  ?>" required>
                </div>

                <div class="col-auto">
                    <label>Telephone Client</label>
                    <input type="text" name="tel_acheteur" class="form-control" value="<?= $tel_acheteur ?>" required>
                </div>

                <div class="col-auto">
                    <label>Ville Client</label>
                    <input type="text" name="ville_acheteur" class="form-control" value="<?= $ville_acheteur ?>" required  >
                </div>




                <div class="col-auto">
                    <label>Cin Pass RECTO</label>
                    <br>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="file">
                        <input type="hidden" name="fild" value="<?php echo $fild; ?>">

                        <input type="hidden" name="searchTerm1_categorie" value="<?php echo $searchTerm1_categorie; ?>">
                        <input type="hidden" name="searchTerm2_marque" value="<?php echo $searchTerm2_marque; ?>">
                        <input type="hidden" name="searchTerm3_modele" value="<?php echo $searchTerm3_modele; ?>">
                        <input type="hidden" name="searchTerm4_version" value="<?php echo $searchTerm4_version; ?>">
                        <input type="hidden" name="searchTerm5_couleur" value="<?php echo $searchTerm5_couleur; ?>">
                        <input type="hidden" name="searchTerm6_vin" value="<?php echo $searchTerm6_vin; ?>">
                        <input type="hidden" name="entrepot" value="<?php echo $entrepot; ?>">
                        <input type="hidden" name="nom_acheteur" value="<?php echo $nom_acheteur; ?>">
                        <input type="hidden" name="prenom_acheteur" value="<?php echo $prenom_acheteur; ?>">
                        <input type="hidden" name="tel_acheteur" value="<?php echo $tel_acheteur; ?>">
                        <input type="hidden" name="ville_acheteur" value="<?php echo $ville_acheteur; ?>">
                        <input type="hidden" name="cin_pass_recto_acheteur" value="<?php echo $cin_pass_recto_acheteur; ?>">
                        <input type="hidden" name="cin_pass_verso_acheteur" value="<?php echo $cin_pass_verso_acheteur; ?>">
                        <input type="submit" name="submit" value="Upload">
                    </form>
                </div>






		        <br>
		        <button type="submit" name="action" value="commander" class="btn btn-primary">Commander</button>
		    <?php /*   ?>  <button type="submit" name="action" value="modifier" class="btn btn-warning" <?= empty($r6->vin) ? 'disabled' : '' ?>>Modifier</button>
		        <button type="submit" name="action" value="supprimer" class="btn btn-danger" <?= empty($r6->vin) ? 'disabled' : '' ?>>Supprimer</button>
            <?php */  ?>

        </form>

    </div>
    </div>

<?php } ?>







<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
