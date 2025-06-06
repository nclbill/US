<?php

require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';


if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID commande invalide.");
}

$commande_id = (int) Input::get('id');

$client_infos = $db->query("SELECT * FROM commandes WHERE id = ?", [$commande_id])->first();
if (!$client_infos->id) {
    die("Client non trouvé.");
}


// Récupération du chemin relatif depuis la base de données
$cin_recto = $client_infos->cin_pass_recto_acheteur;
$cin_verso = $client_infos->cin_pass_verso_acheteur;

// Construction des chemins web
$cin_recto_web  = $us_url_root . $cin_recto;
$cin_verso_web  = $us_url_root . $cin_verso;

// Construction des chemins absolus
$cin_recto_full = $abs_us_root . $us_url_root . $cin_recto;
$cin_verso_full = $abs_us_root . $us_url_root . $cin_verso;


function fileExistsAndValid($absolutePath) {
    return file_exists($absolutePath) && is_file($absolutePath);
}
?>

<div class="content mt-3">
  <h3>Informations du client</h3>

  <div class="card">
    <div class="card-body">
         <p><strong>Nom :</strong> <?= htmlspecialchars($client_infos->nom_acheteur) ?></p>
         <p><strong>Prénom :</strong> <?= htmlspecialchars($client_infos->prenom_acheteur) ?></p>
         <p><strong>Téléphone :</strong> <?= htmlspecialchars($client_infos->tel_acheteur) ?></p>
         <p><strong>Ville :</strong> <?= htmlspecialchars($client_infos->ville_acheteur) ?></p>

      <hr>

      <div class="row">
        <div class="col-md-6 text-center">
          <h5>CIN Recto</h5>

          <?php if (fileExistsAndValid($cin_recto_full)): ?>
            <img src="<?= htmlspecialchars($cin_recto_web) ?>" class="img-fluid mb-2 border" style="max-height:300px"><br>
            <a href="<?= htmlspecialchars($cin_recto_web) ?>" download class="btn btn-sm btn-primary">Télécharger</a>
          <?php else: ?>
            <div class="text-muted">Non disponible</div>
          <?php endif; ?>


        </div>

        <div class="col-md-6 text-center">
          <h5>Cin Verso</h5>

          <?php if (fileExistsAndValid($cin_verso_full)): ?>
            <img src="<?= htmlspecialchars($cin_verso_web) ?>" class="img-fluid mb-2 border" style="max-height:300px"><br>
            <a href="<?= htmlspecialchars($cin_verso_web) ?>" download class="btn btn-sm btn-primary">Télécharger</a>
          <?php else: ?>
            <div class="text-muted">Non disponible</div>
          <?php endif; ?>



        </div>
      </div>
    </div>
  </div>

  <a href="commandes_traitement.php" class="btn btn-secondary mt-3">← Retour Aux Traitement Des Commandes</a>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
