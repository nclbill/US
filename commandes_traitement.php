<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';


if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}


// Vérification d'un privilège admin simple
//if (!hasPerm([2], $user->data()->id)) {
  //  die("Accès refusé.");
//}

$db = DB::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cmd_id = $_POST['commande_id'] ?? null;
    if (isset($_POST['valider'])) {
        $db->update('commandes', $cmd_id, ['status_commande' => 'Validee']);
        $status = 'Commande validée.';
    } elseif (isset($_POST['annuler'])) {
        $commande = $db->get('commandes', ['id', '=', $cmd_id])->first();
        $db->update('produits', $commande->produit_id, ['status' => 'Disponible', 'date_reservation' => null]);
        $db->delete('commandes', $cmd_id);
        $status = 'Commande annulée.';
    } elseif (isset($_POST['modifier_delai'])) {
        $nouveau = (int) $_POST['delai'] ?? 24;
        $commande = $db->get('commandes', ['id', '=', $cmd_id])->first();
        $db->update('produits', $commande->produit_id, ['delai_reservation' => $nouveau]);
        $status = 'Délai modifié.';
    }
}

$commandes = $db->query(
    "SELECT c.*, p.categorie, p.marque, p.modele, p.version, p.couleur, p.entrepot, p.date_reservation, p.delai_reservation
     FROM commandes c
     JOIN produits p ON c.produit_id = p.id
     ORDER BY c.date_commande DESC"
)->results();

print_r($db->results());
?>
<div class="container">
  <h2>Traitement des commandes</h2>
  <?php if (!empty($status)): ?><p style="color:green;"><?= $status ?></p><?php endif; ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Commande</th>
        <th>Statut</th>
        <th>Date</th>
        <th>Délai</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($commandes as $cmd): ?>
        <tr>
          <td><?= e($cmd->categorie . ' ' . $cmd->marque . ' ' . $cmd->modele . ' ' . $cmd->version . ' ' . $cmd->couleur) ?></td>
          <td><?= e($cmd->status_commande) ?></td>
          <td><?= e($cmd->date_commande) ?></td>
          <td>
            <form method="POST" style="display:inline-flex; gap:5px;">
              <input type="hidden" name="commande_id" value="<?= $cmd->id ?>">
              <input type="number" name="delai" value="<?= e($cmd->delai_reservation ?? 24) ?>" min="1" max="168">
              <button name="modifier_delai" class="btn btn-sm btn-secondary">Modifier</button>
            </form>
          </td>
          <td>
            <?php if ($cmd->status_commande === 'Reserve'): ?>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="commande_id" value="<?= $cmd->id ?>">
                <button name="valider" class="btn btn-sm btn-success">Valider</button>
              </form>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="commande_id" value="<?= $cmd->id ?>">
                <button name="annuler" class="btn btn-sm btn-danger">Annuler</button>
              </form>
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
