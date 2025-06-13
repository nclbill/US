<?php
// -------------------------------------------------
// produits_ajustement_par_commande.php
// -------------------------------------------------

require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}

$db = DB::getInstance();

// Récupération des numéros de commande d'achat distincts non archivés
function getCommandesAchats($db) {
    $sql = "
        SELECT DISTINCT numero_commande_achat
        FROM produits
        WHERE archive = 0
        ORDER BY numero_commande_achat ASC
    ";
    $r = $db->query($sql);
    if ($r->count()) {
        return array_map(fn($o) => $o->numero_commande_achat, $r->results());
    }
    return [];
}

// Récupération des produits par numéro de commande d'achat
function getProduitsByCommande($db, $commande) {
    $r = $db->query(
        "SELECT * FROM produits WHERE archive = 0 AND numero_commande_achat = ?",
        [$commande]
    );
    return $r->results();
}

// Validation VIN unique
function vinUnique($db, $vin, $id) {
    $r = $db->query(
        "SELECT id FROM produits WHERE vin = ? AND id != ?",
        [$vin, $id]
    );
    return $r->count() === 0;
}

$messages = [];
if (Input::exists('post') && Token::check(Input::get('csrf'))) {
    if (Input::get('update_produits') && Input::get('produits')) {
        $updates = Input::get('produits');
        $commande = Input::get('commande');
        $firstModified = null;

        foreach ($updates as $id => $fields) {
            $id = (int)$id;
            $f = array_map('trim', $fields);

            if (in_array('', $f, true)) {
                $messages[] = "Produit #{$id} : tous les champs obligatoires.";
                continue;
            }
            if (!vinUnique($db, $f['vin'], $id)) {
                $messages[] = "Produit #{$id} : VIN unique déjà utilisé.";
                continue;
            }

            $db->update('produits', $id, [
                'categorie' => $f['categorie'],
                'marque'    => $f['marque'],
                'modele'    => $f['modele'],
                'version'   => $f['version'],
                'couleur'   => $f['couleur'],
                'vin'       => $f['vin'],
                'entrepot'  => $f['entrepot'],
                'numero_commande_achat' => $f['numero_commande_achat']
            ]);

            $firstModified = $firstModified ?? $id;
        }

        if ($firstModified) {
            Redirect::to("produits_ajustement_cmd_acht.php?commande=".urlencode($commande)."#prod-$firstModified");
        }
    }
}

$commandes = getCommandesAchats($db);
$current = Input::get('commande') ?? null;
$produits = $current ? getProduitsByCommande($db, $current) : [];

?>

<div class="container">
  <h2>Ajustement des produits par numéro de commande d'achat</h2>

  <?php if ($messages): ?>
    <div class="alert alert-danger">
      <ul><?= implode('', array_map(fn($m) => "<li>$m</li>", $messages)) ?></ul>
    </div>
  <?php endif; ?>

  <div>
    <?php foreach ($commandes as $c): ?>
      <a class="btn btn-<?= $c === $current ? 'primary' : 'secondary' ?>"
         href="?commande=<?= urlencode($c) ?>">
         <?= htmlspecialchars($c) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($current): ?>
    <h3>Numéro de commande d'achat : <?= htmlspecialchars($current) ?></h3>

    <?php if ($produits): ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
        <input type="hidden" name="commande" value="<?= htmlspecialchars($current) ?>">
        <input type="hidden" name="update_produits" value="1">

        <table class="table table-bordered mt-3">
          <thead>
            <tr>
              <th style="width:20px;"></th>
              <th>ID</th><th>Catégorie</th><th>Marque</th><th>Modèle</th>
              <th>Version</th><th>Couleur</th><th>VIN</th><th>N Cmd Acht</th><th>Entrepôt</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($produits as $p): ?>
              <tr id="prod-<?= $p->id ?>">
                <td><input type="checkbox" class="mark" /></td>
                <td><?= $p->id ?></td>

                <?php foreach (['categorie','marque','modele','version','couleur','vin','numero_commande_achat'] as $f): ?>
                  <td>
                    <input class="form-control form-control-sm"
                           name="produits[<?= $p->id ?>][<?= $f ?>]"
                           value="<?= htmlspecialchars($p->$f) ?>" required>
                  </td>
                <?php endforeach; ?>

                <td>
                  <input class="form-control form-control-sm"
                         name="produits[<?= $p->id ?>][entrepot]"
                         value="<?= htmlspecialchars($p->entrepot) ?>" required>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <button class="btn btn-success">Enregistrer modifications</button>
        <button type="button" id="exportCSV" class="btn btn-info">Exporter CSV</button>
      </form>
    <?php else: ?>
      <div class="alert alert-warning">Aucun produit pour cette commande.</div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const key = 'marks_<?= md5($current) ?>';
  const saved = JSON.parse(localStorage.getItem(key) || '{}');

  document.querySelectorAll('.mark').forEach(chk => {
    const row = chk.closest('tr');
    const id = row.id.split('-')[1];
    chk.checked = saved[id] ?? false;
    chk.addEventListener('change', () => {
      saved[id] = chk.checked;
      localStorage.setItem(key, JSON.stringify(saved));
      row.classList.toggle('table-warning', chk.checked);
    });
    if (chk.checked) row.classList.add('table-warning');
  });

  document.getElementById('exportCSV').addEventListener('click', () => {
    const params = new URLSearchParams(window.location.search);
    params.set('export_csv','1');
    window.location = window.location.pathname + '?' + params.toString();
  });
});
</script>

<?php
// Export CSV
if (Input::exists('get') && Input::get('export_csv') && $current) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="produits_'.preg_replace('/[^a-z0-9]/i','_', $current).'.csv"');
  $out = fopen('php://output','w');
  fputcsv($out, ['ID','Categorie','Marque','Modele','Version','Couleur','VIN','numero_commande_achat','Entrepot']);
  foreach ($produits as $p) {
    fputcsv($out, [
      $p->id, $p->categorie, $p->marque, $p->modele,
      $p->version, $p->couleur, $p->vin, $p->numero_commande_achat, $p->entrepot
    ]);
  }
  exit;
}

require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php';
?>
