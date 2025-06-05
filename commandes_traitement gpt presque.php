
<?php
date_default_timezone_set('Africa/Casablanca');
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

// Traitement des actions envoyées par POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	echo "<pre>";
print_r($_POST);
echo "</pre>";
    $commande_id = Input::get('commande_id');
    $produit_id = Input::get('produit_id');

    if (Input::exists() && Token::check(Input::get('csrf'))) {
        if (Input::get('valider')) {
            $db->update('commandes', $commande_id, ['status_commande' => 'Validée']);
            $db->update('produits', $produit_id, [
                'status' => 'Vendu'
            ]);
        } elseif (Input::get('annuler')) {
            $db->update('commandes', $commande_id, ['status_commande' => 'Annulée']);
            $db->update('produits', $produit_id, [
                'status' => 'Disponible'
            ]);



					} elseif (isset($_POST['modifier_delai']) && is_numeric($_POST['nouveau_delai'])) {
    $delai = (int)Input::get('nouveau_delai');
    $commande_id = Input::get('commande_id');

    echo "<p>⏱ Vérification commande_id : $commande_id</p>";

    // Récupérer la commande depuis la base
    $commande_actuelle = $db->query("SELECT delai FROM commandes WHERE id = ?", [$commande_id])->first();
		echo "<p>Valeur brute du délai en base : </p>";
		var_dump($commande_actuelle->delai);
    if ($commande_actuelle) {
        $dateActuelle = $commande_actuelle->delai; // string date

        echo "<p>Date actuelle du délai : $dateActuelle</p>";

        // Convertir en objet DateTime
        $dateObj = new DateTime($dateActuelle);

        // Ajouter $delai jours
        $dateObj->modify("+$delai days");

        // Formater la nouvelle date pour SQL
        $nouvelleDate = $dateObj->format('Y-m-d H:i:s');

        echo "<p>Nouvelle date calculée : $nouvelleDate</p>";

        // Mettre à jour la base
        if ($db->update('commandes', $commande_id, ['delai' => $nouvelleDate])) {
            $message = "<div class='alert alert-success'>Délai modifié avec succès : $nouvelleDate</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la modification du délai.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Commande introuvable.</div>";
    }
}







		//		elseif (Input::get('modifier_delai') && is_numeric(Input::get('nouveau_delai'))) {
      //      $delai = (int)Input::get('nouveau_delai');
      //      $nouvelleDate = date('Y-m-d H:i:s', strtotime("+$delai minutes"));
      //      $db->update('commandes', $commande_id, ['delai' => $nouvelleDate]);
      //  }
    }
}

// Récupération des commandes en attente (status = 'Reserve')
$commandes = $db->query("
    SELECT c.*,
           c.delai AS delai,
           p.categorie, p.marque, p.modele, p.version, p.couleur, p.entrepot,
           p.status AS produit_status, p.id AS produit_id
    FROM commandes c
    JOIN produits p ON c.produit_id = p.id
    WHERE c.status_commande = 'Reserve'
    ORDER BY c.delai ASC
")->results();

if (isset($commande_id)) {
    $commande_test = $db->query("SELECT delai FROM commandes WHERE id = ?", [$commande_id])->first();
    if ($commande_test) {
        echo "<pre>Delai de la commande après update : " . $commande_test->delai . "</pre>";
    }
}
?>
<?php
if (empty($commandes)) {
    echo "<div class='alert alert-warning'>Aucune commande en attente trouvée.</div>";
}
if (!empty($message)) {
    echo $message;
}
?>
<div class="container">
    <h3 class="mt-4">Commandes en attente</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Modifier Délai</th>
                <th>Revendeur</th>
                <th>Temps restant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
					<pre>
<?php //var_dump($commandes); ?>
</pre>
        <?php foreach ($commandes as $commande):
          //  $now = new DateTime();
          //  $dateDelai = new DateTime($commande->delai);

					    $now = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
					    $dateDelai = DateTime::createFromFormat('Y-m-d H:i:s', $commande->delai, new DateTimeZone('Africa/Casablanca'));
					    $diffSec = max(0, $dateDelai->getTimestamp() - $now->getTimestamp());

					    if ($diffSec > 0) {
					        $jours = floor($diffSec / 86400);
					        $heures = floor(($diffSec % 86400) / 3600);
					        $minutes = floor(($diffSec % 3600) / 60);

					        $tempsRestant = [];
					        if ($jours > 0) $tempsRestant[] = "$jours j";
					        if ($heures > 0 || $jours > 0) $tempsRestant[] = "$heures h";
					        $tempsRestant[] = "$minutes min";

					        if ($diffSec < 1800) {
					            $classeDelai = 'text-danger'; // Moins de 30 min
					        } elseif ($diffSec < 7200) {
					            $classeDelai = 'text-warning'; // Moins de 2h
					        } else {
					            $classeDelai = 'text-success'; // 2h ou plus
					        }

					        $affichageDelai = "<span class='$classeDelai'>" . implode(' ', $tempsRestant) . " restantes</span>";
					    } else {
					        $affichageDelai = "<span class='text-danger'>Expirée</span>";
					    }

							if (isset($_POST['modifier_delai']) && is_numeric($_POST['nouveau_delai'])) {
    $delai = (int)Input::get('nouveau_delai');
    $commande_id = Input::get('commande_id');

    // Ton code de modification date ici...

    // Après la mise à jour, fais ce test de lecture dans la base :
    $commande_test = $db->query("SELECT delai FROM commandes WHERE id = ?", [$commande_id])->first();
    //echo "<pre>Delai de la commande après update : " . $commande_test->delai . "</pre>";
Redirect::to("commandes_traitement.php");


}
				//			echo "<p>Commande ID : " . Input::get('commande_id') . "</p>";
//echo "<p>Nouveau délai : " . Input::get('nouveau_delai') . " minutes</p>";

        ?>
            <tr>
                <td>
                    <?= "{$commande->categorie} / {$commande->marque} / {$commande->modele} / {$commande->version} / {$commande->couleur}" ?><br>
                    Entrepôt : <?= $commande->entrepot ?><br>
                    Produit ID : <?= $commande->produit_id ?><br>
                    Statut : <?= $commande->produit_status ?><br>
                    Réservé jusqu'au : <?= $commande->delai ?>
                </td>
                <td>
                    <form method="post" class="mb-2">
                        <input type="hidden" name="csrf" value="<?= Token::generate() ?>">
                        <input type="hidden" name="commande_id" value="<?= $commande->id ?>">
                        <input type="hidden" name="produit_id" value="<?= $commande->produit_id ?>">
                        <input type="number" name="nouveau_delai" class="form-control" min="1" placeholder="Min">
                        <button type="submit" name="modifier_delai" class="btn btn-warning btn-sm mt-1">Modifier délai</button>
                    </form>
                </td>
                <td>
                    <?= $commande->Nom_revendeur ?><br>
                    <a href="infos_client.php?id=<?= $commande->id ?>" class="btn btn-info btn-sm mt-1">Voir client</a>
                </td>
                <td>
                <?= $affichageDelai ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="csrf" value="<?= Token::generate() ?>">
                        <input type="hidden" name="commande_id" value="<?= $commande->id ?>">
                        <input type="hidden" name="produit_id" value="<?= $commande->produit_id ?>">
                        <button type="submit" name="valider" class="btn btn-success btn-sm">Valider</button>
                        <button type="submit" name="annuler" class="btn btn-danger btn-sm">Annuler</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
