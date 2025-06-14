<?php

require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}

$db = DB::getInstance();
$csrf = Token::generate();
$messages = [];

function getOptions($field, $filters = []) {
    global $db;
    $sql = "SELECT DISTINCT $field FROM produits WHERE archive = 0 AND status = 'Disponible'";
    $params = [];
    foreach ($filters as $f => $v) {
        $sql .= " AND $f = ?";
        $params[] = $v;
    }
    $sql .= " ORDER BY $field ASC";
    return $db->query($sql, $params)->results();
}

$selection = [];
$fields = ['categorie', 'marque', 'modele', 'version', 'couleur', 'entrepot'];
foreach ($fields as $f) {
    if (Input::get($f)) {
        $selection[$f] = Input::get($f);
    } else {
        break;
    }
}

$produit = null;
if (count($selection) === count($fields)) {
    $sql = "SELECT * FROM produits WHERE archive = 0 AND status = 'Disponible'";
    $params = [];
    foreach ($selection as $k => $v) {
        $sql .= " AND $k = ?";
        $params[] = $v;
    }
    $res = $db->query($sql, $params)->results();
    if (count($res) === 1) {
        $produit = $res[0];
    }
}

if (Input::exists('post') && Token::check(Input::get('csrf'))) {
    $id = Input::get('produit_id');
    $nom = trim(Input::get('client_nom'));
    $tel = trim(Input::get('client_tel'));
    $ville = trim(Input::get('client_ville'));
    $recto = $_FILES['cni_recto'];
    $verso = $_FILES['cni_verso'];

    if ($id && $nom && $tel && $ville && $recto && $verso) {
        $p = $db->query("SELECT * FROM produits WHERE id = ? AND status = 'Disponible' AND archive = 0", [$id])->first();
        if ($p) {
            $path = 'uploads/cni/';
            $filename_recto = uniqid().'_recto_'.basename($recto['name']);
            $filename_verso = uniqid().'_verso_'.basename($verso['name']);
            move_uploaded_file($recto['tmp_name'], $path.$filename_recto);
            move_uploaded_file($verso['tmp_name'], $path.$filename_verso);

            $db->update('produits', $id, [
                'status' => 'Reserve',
                'date_reservation' => date('Y-m-d H:i:s')
            ]);

            $db->insert('commandes', [
                'produit_id' => $id,
                'nom_client' => $nom,
                'tel_client' => $tel,
                'ville_client' => $ville,
                'cni_recto' => $filename_recto,
                'cni_verso' => $filename_verso,
                'date_commande' => date('Y-m-d H:i:s'),
                'user_id' => $user->data()->id,
                'status_commande' => 'Reserve'
            ]);

            Redirect::to('commande-mobile.php?success=1');
        } else {
            $messages[] = "Produit non disponible.";
        }
    } else {
        $messages[] = "Tous les champs sont requis.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Passer Une Commande</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    .step-header { font-weight: bold; margin-top: 20px; }
    .step-button { margin: 4px 4px 8px 0; }
    .stacked { display: flex; flex-wrap: wrap; }
    .selected-steps { margin-bottom: 15px; }
  </style>
</head>
<body>
<div class="container py-4">
  <h4 class="text-center"><i class="bi bi-funnel"></i> Passer Une Commande</h4>

  <?php if (!empty($messages)): ?>
    <div class="alert alert-danger"><ul><?php foreach ($messages as $m) echo "<li>$m</li>"; ?></ul></div>
  <?php elseif (Input::get('success')): ?>
    <div class="alert alert-success">✅ Commande enregistrée avec succès.</div>
  <?php endif; ?>

  <?php if (!empty($selection)): ?>
    <div class="selected-steps">
      <?php foreach ($selection as $k => $v): ?>
        <a class="btn btn-primary btn-sm me-1" href="?<?= http_build_query(array_slice($selection, 0, array_search($k, array_keys($selection)))) ?>">
          <?= ucfirst($k) ?>: <?= htmlspecialchars($v) ?> <i class="bi bi-arrow-counterclockwise"></i>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php foreach ($fields as $f): ?>
    <?php if (!isset($selection[$f])): ?>
      <div class="step-header">Choisir <?= ucfirst($f) ?> :</div>
      <div class="stacked">
        <?php $options = getOptions($f, $selection); foreach ($options as $o): ?>
          <a class="btn btn-outline-primary step-button" href="?<?= http_build_query(array_merge($selection, [$f => $o->$f])) ?>">
            <?= htmlspecialchars($o->$f) ?>
          </a>
        <?php endforeach; ?>
      </div>
      <?php break; ?>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php if ($produit): ?>
    <hr>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Produit sélectionné</h5>
        <p class="card-text">
          <?= $produit->categorie ?> / <?= $produit->marque ?> / <?= $produit->modele ?> / <?= $produit->version ?><br>
          Couleur : <?= $produit->couleur ?> – Entrepôt : <?= $produit->entrepot ?>
        </p>
      </div>
    </div>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">
      <input type="hidden" name="produit_id" value="<?= $produit->id ?>">

      <div class="mb-3">
        <label class="form-label">Nom client</label>
        <input class="form-control" name="client_nom" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input class="form-control" name="client_tel" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Ville</label>
        <input class="form-control" name="client_ville" required>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label class="form-label">CNI Recto</label>
          <input class="form-control" type="file" name="cni_recto" accept="image/*" capture="environment" >
        </div>

        <div class="mb-3">
          <label class="form-label">CNI Verso</label>
          <input class="form-control" type="file" name="cni_verso" accept="image/*" capture="environment" >
        </div>

      <button class="btn btn-success w-100" type="submit">
        <i class="bi bi-check-circle"></i> Valider la commande
      </button>
    </form>
  <?php endif; ?>
</div>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
