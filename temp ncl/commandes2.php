<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
//if (!securePage($_SERVER['PHP_SELF'])) { die(); }

//info selection article
$searchTerm1_categorie = Input::get('searchTerm1_categorie');
$searchTerm2_marque = Input::get('searchTerm2_marque');
$searchTerm3_modele= Input::get('searchTerm3_modele');
$searchTerm4_version = Input::get('searchTerm4_version');
$searchTerm5_couleur = Input::get('searchTerm5_couleur');
$searchTerm6_vin = Input::get('searchTerm6_vin');
$entrepot = Input::get('entrepot');
//info client
$nom_acheteur = Input::get('nom_acheteur');
$prenom_acheteur = Input::get('prenom_acheteur');
$tel_acheteur = Input::get('tel_acheteur');
$ville_acheteur = Input::get('ville_acheteur');

$file = Input::get('file');
$fild = Input::get('fild');

// Récupération des catégories
$query = $db->query("SELECT DISTINCT categorie FROM produits", []);
$count = $query->count();
$results = $query->results();
?>

<?php if ($count > 0) { ?>
	<div class="row">
    <h2> <p style="color:red;"><?php echo "Espace Commandes";?> </h6>

		<div class="col-md-9">
		<h6> <?php echo "$searchTerm1_categorie";?> <?php echo "$searchTerm2_marque";?>  <?php echo "$searchTerm3_modele";?>   <?php echo "$searchTerm4_version";?>     <?php echo "$searchTerm5_couleur"?>     <?php echo "$entrepot";?></h6>

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





            <!-- Colonne des entrepot(si une couleur est sélectionnée) -->
            <?php if (!empty($searchTerm5_couleur)) {

              $query7 = $db->query("SELECT DISTINCT entrepot FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ?and couleur = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur]);
              $count7 = $query7->count();
              $results7 = $query7->results();

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
            <?php } }?>

          </div>
            </div>
    </div>



            <!-- FORMULAIRE D'AJOUT / MODIFICATION -->
            <div class="col-md-3">

							<form action="votre_page_de_traitement.php" method="post" enctype="multipart/form-data">
							    <!-- Autres champs de votre formulaire -->
							    <label for="cin_pass_recto_acheteur">CIN ou Passeport Recto :</label>
							    <input type="file" name="cin_pass_recto_acheteur" id="cin_pass_recto_acheteur" accept="image/*, .pdf, .docx">

							    <label for="cin_pass_verso_acheteur">CIN ou Passeport Verso :</label>
							    <input type="file" name="cin_pass_verso_acheteur" id="cin_pass_verso_acheteur" accept="image/*, .pdf, .docx">

							    <!-- Autres champs du formulaire -->
							    <label for="searchTerm1_categorie">Catégorie :</label>
							    <input type="text" name="searchTerm1_categorie" id="searchTerm1_categorie">

							    <label for="searchTerm2_marque">Marque :</label>
							    <input type="text" name="searchTerm2_marque" id="searchTerm2_marque">

							    <label for="searchTerm3_modele">Modèle :</label>
							    <input type="text" name="searchTerm3_modele" id="searchTerm3_modele">

							    <label for="searchTerm4_version">Version :</label>
							    <input type="text" name="searchTerm4_version" id="searchTerm4_version">

							    <label for="searchTerm5_couleur">Couleur :</label>
							    <input type="text" name="searchTerm5_couleur" id="searchTerm5_couleur">

							    <label for="entrepot">Entrepôt :</label>
							    <input type="text" name="entrepot" id="entrepot">

							    <label for="nom_acheteur">Nom de l'acheteur :</label>
							    <input type="text" name="nom_acheteur" id="nom_acheteur">

							    <label for="prenom_acheteur">Prénom de l'acheteur :</label>
							    <input type="text" name="prenom_acheteur" id="prenom_acheteur">

							    <label for="tel_acheteur">Numéro de téléphone de l'acheteur :</label>
							    <input type="text" name="tel_acheteur" id="tel_acheteur">

							    <label for="ville_acheteur">Ville de l'acheteur :</label>
							    <input type="text" name="ville_acheteur" id="ville_acheteur">

							    <button type="submit">Soumettre</button>
							</form>


        

        </div>
        </div>



<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
