<?php
date_default_timezone_set('Africa/Casablanca');

require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// Vérification de la sécurité de la page
if (!securePage($_SERVER['PHP_SELF'])) {
    die('Accès non autorisé');
}

// Mise à jour des commandes expirées
try {
    $db->query("
        UPDATE commandes
        SET status_commande = 'Expiree'
        WHERE status_commande = 'Reserve'
          AND delai < NOW()
    ");

    $db->query("
        UPDATE produits
        JOIN commandes ON commandes.produit_id = produits.id
        SET produits.status = 'Disponible'
        WHERE commandes.status_commande = 'Expiree'
    ");
} catch (Exception $e) {
    // Log de l'erreur ou affichage simple (à adapter)
    error_log("Erreur mise à jour commandes expirées : " . $e->getMessage());
}

// Récupération des commandes expirées avec infos produit, revendeur et client
// (ajuster les noms de colonnes et tables selon ta base de données)
$commandes = $db->query("
    SELECT
        c.id AS commande_id,
        c.produit_id,
        c.date_commande,
        c.delai,
        c.status_commande,
        p.categorie, p.marque, p.modele, p.version, p.couleur, p.entrepot, p.vin,
        r.raison_sociale AS raison_sociale_revendeur,
        a.nom AS nom_acheteur, a.prenom AS prenom_acheteur, a.ville AS ville_acheteur, a.tel AS tel_acheteur
    FROM commandes c
    JOIN produits p ON p.id = c.produit_id
    LEFT JOIN revendeurs r ON r.id = c.id_client_revendeur
    LEFT JOIN acheteurs a ON a.id = c.id_client_acheteur
    WHERE c.status_commande = 'Expiree'
    ORDER BY c.delai DESC
")->results();

?>

<div class="container mt-5">
  <h3>🛑 Commandes expirées</h3>
  <?php if (count($commandes) === 0): ?>
    <div class="alert alert-info">Aucune commande expirée pour le moment.</div>
  <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th>ID Commande</th>
          <th>ID Produit</th>
          <th>Produit</th>
          <th>Entrepôt</th>
          <th>Revendeur</th>
          <th>Client</th>
          <th>Date de réservation</th>
          <th>Date d’expiration</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commandes as $c): ?>
          <tr>
            <td><?= e($c->commande_id) ?></td>
            <td><?= e($c->produit_id) ?></td>
            <td>
              <?= e($c->categorie) ?> / <?= e($c->marque) ?> / <?= e($c->modele) ?> / <?= e($c->version) ?> / <?= e($c->couleur) ?><br>
              <small><strong>VIN :</strong> <?= e($c->vin) ?></small>
            </td>
            <td><?= e($c->entrepot) ?></td>
            <td><?= e($c->raison_sociale_revendeur ?? 'N/A') ?></td>
            <td>
              <?= e($c->nom_acheteur) ?> <?= e($c->prenom_acheteur) ?><br>
              <small><?= e($c->ville_acheteur) ?> - <?= e($c->tel_acheteur) ?></small>
            </td>
            <td><?= e($c->date_commande) ?></td>
            <td><?= e($c->delai) ?></td>
            <td>
              <a href="commande_details.php?id=<?= e($c->commande_id) ?>" class="btn btn-sm btn-primary">Détails</a>
              <a href="supprimer_commande.php?id=<?= e($c->commande_id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette commande ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
