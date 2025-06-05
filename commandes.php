<?php
date_default_timezone_set('Africa/Casablanca');
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}

///////////////////////dinamique
$searchTerm1_categorie = Input::get('searchTerm1_categorie');
$searchTerm2_marque = Input::get('searchTerm2_marque');
$searchTerm3_modele= Input::get('searchTerm3_modele');
$searchTerm4_version = Input::get('searchTerm4_version');
$searchTerm5_couleur = Input::get('searchTerm5_couleur');
$searchTerm6_vin = Input::get('searchTerm6_vin');
$entrepot = Input::get('entrepot');

// Variables pour afficher un message après soumission
$statusMsg = '';

// Récupération des catégories
$query = $db->query("SELECT DISTINCT categorie FROM produits WHERE status = ?", ["disponible"]);
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
                $query2 = $db->query("SELECT DISTINCT marque FROM produits WHERE categorie = ? and status = ?", [$searchTerm1_categorie,"disponible"]);
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
                $query3 = $db->query("SELECT DISTINCT modele FROM produits WHERE categorie = ? and marque = ? and status = ?", [$searchTerm1_categorie,$searchTerm2_marque,"disponible"]);
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
                $query4 = $db->query("SELECT DISTINCT version FROM produits WHERE categorie = ? and marque = ? and modele= ? and status = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,"disponible"]);
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
                $query5 = $db->query("SELECT DISTINCT couleur FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ? and status = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,"disponible"]);
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
            <?php }} ?>





            <!-- Colonne des VIN (si une couleur est sélectionnée) -->
            <?php if (!empty($searchTerm5_couleur)) {

              $query7 = $db->query("SELECT DISTINCT entrepot FROM produits WHERE categorie = ? and marque = ? and modele= ? and version = ?and couleur = ? and status = ?", [$searchTerm1_categorie,$searchTerm2_marque,$searchTerm3_modele,$searchTerm4_version,$searchTerm5_couleur,"disponible"]);
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
						*/



//Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et sécurisation des données
    $categorie = htmlspecialchars(Input::get('searchTerm1_categorie'));
    $marque = htmlspecialchars(Input::get('searchTerm2_marque'));
    $modele = htmlspecialchars(Input::get('searchTerm3_modele'));
    $version = htmlspecialchars(Input::get('searchTerm4_version'));
    $couleur = htmlspecialchars(Input::get('searchTerm5_couleur'));
    $entrepot = htmlspecialchars(Input::get('entrepot'));

    $nom_acheteur = htmlspecialchars(Input::get('nom_acheteur'));
    $prenom_acheteur = htmlspecialchars(Input::get('prenom_acheteur'));
    $tel_acheteur = htmlspecialchars(Input::get('tel_acheteur'));
    $ville_acheteur = htmlspecialchars(Input::get('ville_acheteur'));

    $id_client_revendeur = $user->data()->id;
    $Nom_revendeur = $user->data()->lname;

    // Configuration de l'upload
    $targetDir = "uploads/";
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx');
    $maxFileSize = 5 * 1024 * 1024; // 5 Mo

    // Vérification et traitement des fichiers
    $uploadedFiles = [];

    foreach (['cin_pass_recto_acheteur', 'cin_pass_verso_acheteur'] as $fileField) {
        if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
            $fileName = str_replace(' ', '_', basename($_FILES[$fileField]['name']));
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $fileSize = $_FILES[$fileField]['size'];
            $targetFilePath = $targetDir . time() . "_" . uniqid() . "_" . $fileName;

            // Vérification MIME côté serveur protection contre les exécutables déguisés
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES[$fileField]['tmp_name']);
            finfo_close($finfo);
            if (!in_array($mime, ['image/jpeg', 'image/png', 'application/pdf'])) {
                die("Fichier non conforme");
            }
            // Vérification du type de fichier
            if (!in_array($fileType, $allowedTypes)) {
                $statusMsg = "Type de fichier non autorisé pour : " . $fileField;
                break;
            }

            // Vérification de la taille du fichier
            if ($fileSize > $maxFileSize) {
                $statusMsg = "Le fichier est trop volumineux pour : " . $fileField;
                break;
            }

            // Déplacement du fichier
            if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $targetFilePath)) {
                $uploadedFiles[$fileField] = $targetFilePath;
            } else {
                $statusMsg = "Erreur lors du téléchargement du fichier : " . $fileField;
                break;
            }
        }
    }







    // Nettoyage des réservations expirées avant de chercher un produit
    $expirationMinutes = 1440; // Durée limite d'une réservation temporaire
  //  $expirationTime = date('Y-m-d H:i:s', strtotime("-{$expirationMinutes} minutes"));
    $expirationLimit = date('Y-m-d H:i:s', strtotime("-{$expirationMinutes} minutes"));

    //  ligne pour générer une vraie date future d’expiration
     $delaiPrevu = date('Y-m-d H:i:s', strtotime("+{$expirationMinutes} minutes"));


  //  $db->query("UPDATE produits SET status = 'Disponible', date_commande = NULL
    //            WHERE status = 'Reserve' AND date_commande IS NOT NULL AND date_commande < ?",
      //          [$expirationTime]);

                // Libération des produits liés à une commande expirée
              //  $db->query("UPDATE produits
              //              JOIN commandes ON commandes.produit_id = produits.id
              //              SET produits.status = 'Disponible'
              //              WHERE produits.status = 'Reserve'
              //                AND commandes.status_commande = 'Reserve'
              //                AND commandes.date_commande < ?",
              //              [$expirationTime]);

                            // Marquer les commandes expirées comme "Expiree"
                //                     $db->query("UPDATE commandes
                //                        SET status_commande = 'Expiree'
                //                        WHERE status_commande = 'Reserve'
                //                          AND date_commande < ?",
                //                        [$expirationTime]);

                                    // Libération des produits liés à une commande expirée
                                        $db->query("UPDATE produits
                                                    JOIN commandes ON commandes.produit_id = produits.id
                                                    SET produits.status = 'Disponible'
                                                    WHERE produits.status = 'Reserve'
                                                      AND commandes.status_commande = 'Reserve'
                                                      AND commandes.delai < ?",
                                                    [$expirationLimit]);
// Marquer les commandes expirées comme "Expiree"
                                        $db->query("UPDATE commandes
                                                    SET status_commande = 'Expiree'
                                                    WHERE status_commande = 'Reserve'
                                                      AND delai < ?",
                                                    [$expirationLimit]);
                try {
                    // Démarrer la transaction
                    $db->query("START TRANSACTION");

                    // Sélectionner le produit disponible avec verrouillage
                    $stmt = $db->query("SELECT id FROM produits WHERE
                        categorie = ? AND
                        marque = ? AND
                        modele = ? AND
                        version = ? AND
                        couleur = ? AND
                        entrepot = ? AND
                        status = 'Disponible'
                        ORDER BY id ASC
                        LIMIT 1
                        FOR UPDATE", [$categorie, $marque, $modele, $version, $couleur, $entrepot]);

                    $produit = $stmt->first();

                    if (!$produit) {
                        $db->query("ROLLBACK");
                        die("Aucun produit disponible ne correspond à ces critères.");
                    }

                    $produit_id = $produit->id;

                    // Insérer la commande
                    $db->insert('commandes', [
                        "produit_id" => $produit_id,
                        "id_client_revendeur" => $id_client_revendeur,
                        "Nom_revendeur" => $Nom_revendeur,
                        "nom_acheteur" => $nom_acheteur,
                        "prenom_acheteur" => $prenom_acheteur,
                        "tel_acheteur" => $tel_acheteur,
                        "ville_acheteur" => $ville_acheteur,
                        "cin_pass_recto_acheteur" => $uploadedFiles['cin_pass_recto_acheteur'] ?? null,
                        "cin_pass_verso_acheteur" => $uploadedFiles['cin_pass_verso_acheteur'] ?? null,
                        "date_commande" => date("Y-m-d H:i:s"),
                        "status_commande" => "Reserve",
                        "delai" => $delaiPrevu
                    ]);

                    // Mettre à jour le statut du produit
                    $db->update('produits', $produit_id, [
                        "status" => "Reserve",
                      ///////  "date_reservation" => date("Y-m-d H:i:s")
                    ]);

                    // Valider la transaction
                    $db->query("COMMIT");
                    $statusMsg = "Commande enregistrée avec succès.";
                } catch (Exception $e) {
                    $db->query("ROLLBACK");
                    $statusMsg = "Erreur : " . $e->getMessage();
                }
    } // fin du if (empty)

?>
</div>
  </div>
</div>
<?php if (!empty($statusMsg)) : ?>
    <div class="alert alert-info"><?= $statusMsg ?></div>
<?php endif; ?>
<!-- Formulaire HTML -->
<div class="col-md-3">
<h2>Passer une commande</h2>

<?php if (!empty($statusMsg)): ?>
    <p style="color: red;"><?php echo $statusMsg; ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Catégorie :</label>
    <input type="text" name="searchTerm1_categorie" value="<?= $searchTerm1_categorie ?>"required disabled><br>

    <label>Marque :</label>
    <input type="text" name="searchTerm2_marque" value="<?= $searchTerm2_marque ?>" required disabled><br>

    <label>Modèle :</label>
    <input type="text" name="searchTerm3_modele" value="<?= $searchTerm3_modele ?>" required disabled><br>

    <label>Version :</label>
    <input type="text" name="searchTerm4_version" value="<?= $searchTerm4_version ?>" required disabled><br>

    <label>Couleur :</label>
    <input type="text" name="searchTerm5_couleur" value="<?= $searchTerm5_couleur ?>" required disabled><br>

    <label>Entrepôt :</label>
    <input type="text" name="entrepot" value="<?= $entrepot ?>"required disabled><br>

    <h3>Informations de l'acheteur</h3>

    <label>Nom :</label>
    <input type="text" name="nom_acheteur" required><br>

    <label>Prénom :</label>
    <input type="text" name="prenom_acheteur" required><br>

    <label>Téléphone :</label>
    <input type="text" name="tel_acheteur" required><br>

    <label>Ville :</label>
    <input type="text" name="ville_acheteur" required><br>

    <h3>Pièces justificatives</h3>

    <label>CIN / Passeport Recto :</label>
    <input type="file" name="cin_pass_recto_acheteur" accept=".jpg, .jpeg, .png, .gif, .pdf, .docx" required><br>

    <label>CIN / Passeport Verso :</label>
    <input type="file" name="cin_pass_verso_acheteur" accept=".jpg, .jpeg, .png, .gif, .pdf, .docx" required><br>

    <button type="submit">Envoyer la commande</button>
</form>
</div>
</div>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
