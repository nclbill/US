
<?php
date_default_timezone_set('Africa/Casablanca');
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}
$user_id = $user->data()->id;
$user_raison_sociale = $user->data()->raison_sociale;
// Traitement des actions envoyées par POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//	echo "<pre>";
//print_r($_POST);
//echo "</pre>";
    $commande_id = Input::get('commande_id');
    $produit_id = Input::get('produit_id');
		$note_traitement = Input::get('note_traitement');

    if (Input::exists() && Token::check(Input::get('csrf'))) {
        if (Input::get('valider')) {
            $db->update('commandes', $commande_id, ['status_commande' => 'Validée']);
						$db->update('commandes', $commande_id, ['note_traitement' => $note_traitement]);
            $db->update('produits', $produit_id, ['status' => 'Vendu']);
        } elseif (Input::get('rejeter')) {
            $db->update('commandes', $commande_id, ['status_commande' => 'Annulée']);
            $db->update('produits', $produit_id, ['status' => 'disponible']);



					} elseif (isset($_POST['modifier_delai']) && is_numeric($_POST['nouveau_delai'])) {
    $delai = (int)Input::get('nouveau_delai');
    $commande_id = Input::get('commande_id');


    // Récupérer la commande depuis la base
    $commande_actuelle = $db->query("SELECT delai FROM commandes WHERE id = ?", [$commande_id])->first();
	//	echo "<p>Valeur brute du délai en base : </p>";
		//var_dump($commande_actuelle->delai);
    if ($commande_actuelle) {
        $dateActuelle = $commande_actuelle->delai; // string date

        // Convertir en objet DateTime
        $dateObj = new DateTime($dateActuelle);

        // Ajouter $delai jours
        $dateObj->modify("+$delai days");

        // Formater la nouvelle date pour SQL
        $nouvelleDate = $dateObj->format('Y-m-d H:i:s');

      //  echo "<p>Nouvelle date calculée : $nouvelleDate</p>";

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
    WHERE c.status_commande = 'Reserve' AND c.id_client_revendeur = ?
    ORDER BY c.date_commande DESC
", [$user_id])->results();
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
    <h3 class="mt-4">Commandes en attente de <?= $user_raison_sociale ?></h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
								<th>Client</th>
                <th>Délai Av Annulation </th>
                <th>Note Commande</th>
            </tr>
        </thead>
        <tbody>
					<pre>
<?php //var_dump($commandes); ?>
</pre>
        <?php foreach ($commandes as $commande):


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


    // Redirect::to("commandes_traitement.php");


}
        ?>
            <tr>
                <td><?php // produit ?>
                    <?= "{$commande->categorie} / {$commande->marque} / {$commande->modele} / {$commande->version} / {$commande->couleur}" ?><br>
                    Entrepôt : <?= $commande->entrepot ?><br>
                    Num Commande : <?= $commande->id ?><br>
                    Statut : <?= $commande->produit_status ?><br>
                    Réservé jusqu'au :<br> <?= $commande->delai ?>
                </td>

								<td><?php // client ?>
									<center>
                    <br>
                    par: <br>
                    <?= $user_raison_sociale ?><br>
                    pour:<br>
                    <a href="infos_client.php?id=<?= $commande->id ?>&page_precedente=suivi" class="btn btn-info btn-sm mt-1">Voir client</a>
									</center>
								</td>

                <td><?php // delai ?>
									<center>
                    <br>
                    <br>
									<?= $affichageDelai ?><br>
                </center>
                </td>

                <td><?php // note ?>

								<textarea cols="20" rows="5" readonly><?php echo htmlentities($commande->note_commande, ENT_QUOTES, 'utf-8'); ?></textarea>

							</td>


            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
