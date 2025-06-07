<?php
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
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

// Récupération des commandes expirées avec id commande et id produit

$commandes = $db->query("SELECT commandes.id AS commande_id, commandes.produit_id, commandes.*, produits.categorie, produits.marque, produits.modele, produits.version, produits.couleur, produits.entrepot, produits.vin
                        FROM commandes
                        JOIN produits ON produits.id = commandes.produit_id
                        WHERE commandes.status_commande = 'Expiree'
                        ORDER BY commandes.delai DESC")->results();
?>

<div class="container mt-5">
  <h3>🛑 Commandes expirées</h3>
  <?php if (count($commandes) == 0): ?>
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
            <td><?= e($c->categorie) ?> / <?= e($c->marque) ?> / <?= e($c->modele) ?> / <?= e($c->version) ?> / <?= e($c->couleur) ?><br>
              <small><strong>VIN :</strong> <?= e($c->vin) ?></small>
            </td>
            <td><?= e($c->entrepot) ?></td>
            <td><?= e($c->raison_sociale_revendeur) ?></td>
            <td><?= e($c->nom_acheteur) ?> <?= e($c->prenom_acheteur) ?><br>
              <small><?= e($c->ville_acheteur) ?> - <?= e($c->tel_acheteur) ?></small>
            </td>
            <td><?= e($c->date_commande) ?></td>
            <td><?= e($c->delai) ?></td>
            <td>
              <a href="commande_details.php?id=<?= $c->commande_id ?>" class="btn btn-sm btn-primary">Détails</a>
              <a href="supprimer_commande.php?id=<?= $c->commande_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette commande ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
