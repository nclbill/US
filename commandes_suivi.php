<?php
date_default_timezone_set('Africa/Casablanca');
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    }
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}
// Mise à jour des commandes expirées////////////////////////
$result1 = $db->query("
    UPDATE commandes
    SET status_commande = 'Expiree'
    WHERE status_commande = 'Reserve'
      AND delai < NOW()
");

$result2 = $db->query("
    UPDATE produits
    JOIN commandes ON commandes.produit_id = produits.id
    SET produits.status = 'Disponible'
    WHERE commandes.status_commande = 'Expiree'
");
///////////////////////////////////////////////////////////
//archivage des commandes expiree ap
$db->query("
    UPDATE commandes
    SET archive = 1
    WHERE status_commande = 'Expiree'
      AND delai < NOW() - INTERVAL 1 MONTH
");
/////////////////////////////////////
$user_id = $user->data()->id;
$user_raison_sociale = $user->data()->raison_sociale;
// Traitement des actions envoyées par POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $db->update('produits', $produit_id, ['status' => 'Disponible']);
        } elseif (isset($_POST['modifier_delai']) && is_numeric($_POST['nouveau_delai'])) {
            $delai = (int)Input::get('nouveau_delai');
            $commande_id = Input::get('commande_id');

            // Récupérer la commande depuis la base
            $commande_actuelle = $db->query("SELECT delai FROM commandes WHERE id = ?", [$commande_id])->first();

            if ($commande_actuelle) {
                $dateActuelle = $commande_actuelle->delai; // string date
                $dateObj = new DateTime($dateActuelle);
                $dateObj->modify("+$delai days");
                $nouvelleDate = $dateObj->format('Y-m-d H:i:s');

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

// Récupération des commandes expirées du revendeur
$commandes_expirees = $db->query("
    SELECT c.*,
           p.categorie, p.marque, p.modele, p.version, p.couleur, p.entrepot,
           p.status AS produit_status, p.id AS produit_id
    FROM commandes c
    JOIN produits p ON c.produit_id = p.id
    WHERE c.status_commande = 'Expiree' AND c.id_client_revendeur = ?
    ORDER BY c.delai DESC
", [$user_id])->results();
?>

<?php
if (!empty($message)) {
    echo $message;
}
?>

<div class="container">
    <h3 class="mt-4">Commandes en attente de <?= e($user_raison_sociale) ?></h3>

    <?php if (empty($commandes)): ?>
        <div class="alert alert-warning">Aucune commande en attente trouvée.</div>
    <?php else: ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Client</th>
                <th>Délai Av Annulation</th>
                <th>Note Commande</th>
            </tr>
        </thead>
        <tbody>
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
        ?>
            <tr>
                <td>
                    <?= e("{$commande->categorie} / {$commande->marque} / {$commande->modele} / {$commande->version} / {$commande->couleur}") ?><br>
                    Entrepôt : <?= e($commande->entrepot) ?><br>
                    Num Commande : <?= e($commande->id) ?><br>
                    Statut : <?= e($commande->produit_status) ?><br>
                    Réservé jusqu'au :<br> <?= e($commande->delai) ?>
                </td>
                <td>
                    <center>
                    <br>
                    par: <br>
                    <?= e($user_raison_sociale) ?><br>
                    pour:<br>
                    <a href="infos_client.php?id=<?= e($commande->id) ?>&page_precedente=suivi" class="btn btn-info btn-sm mt-1">Voir client</a>
                    </center>
                </td>
                <td><center><?= $affichageDelai ?><br></center></td>
                <td>
                    <textarea cols="20" rows="5" readonly><?= e($commande->note_commande) ?></textarea>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <h3 class="mt-5">Commandes expirées de <?= e($user_raison_sociale) ?></h3>

    <?php if (empty($commandes_expirees)): ?>
        <div class="alert alert-info">Aucune commande expirée pour le moment.</div>
    <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Produit</th>
          <th>Entrepôt</th>
          <th>Date de réservation</th>
          <th>Date d’expiration</th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($commandes_expirees as $c): ?>
          <tr>
            <td><?= e($c->categorie) ?> / <?= e($c->marque) ?> / <?= e($c->modele) ?> / <?= e($c->version) ?> / <?= e($c->couleur) ?><br>
              <small><strong>Num Commande :</strong> <?= e($c->id) ?></small>
            </td>
            <td><?= e($c->entrepot) ?></td>
            <td><?= e($c->date_commande) ?></td>
            <td><?= e($c->delai) ?></td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
