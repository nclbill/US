
<?php
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die("Accès interdit !"); }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = DB::getInstance();
$csrf = Token::generate();
$messages = [];
$uploadDir = 'uploads/filtres/';

// Suppression
if (Input::get('delete') && Token::check(Input::get('csrf'))) {
    $id = (int)Input::get('delete');
    $img = $db->get('produits_images', ['id', '=', $id])->first();
    if ($img) {
        if (file_exists($uploadDir . $img->fichier)) {
            unlink($uploadDir . $img->fichier);
        }
        $db->delete('produits_images', ['id', '=', $id]);
        $messages[] = "Image supprimée.";
        if (file_exists($uploadDir . 'thumbs/' . $img->fichier)) {
    unlink($uploadDir . 'thumbs/' . $img->fichier);
}
    }
}

// Modification
if (Input::get('update_id') && Token::check(Input::get('csrf'))) {
    $id = (int)Input::get('update_id');
    $type = Input::get('type');
    $valeur = trim(Input::get('valeur'));
    $file = $_FILES['nouvelle_image'] ?? null;

    if ($type && $valeur) {
        $update = ['type' => $type, 'valeur' => $valeur];

        if ($file && $file['tmp_name']) {
            $filename = uniqid() . '_' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
            $update['fichier'] = $filename;

            $old = $db->get('produits_images', ['id', '=', $id])->first();
            if ($old && file_exists($uploadDir . $old->fichier)) {
                unlink($uploadDir . $old->fichier);
            }
        }
        $db->update('produits_images', $id, $update);
        $messages[] = "Image mise à jour.";
    }
}

// Insertion
if (Input::get('ajouter') && Token::check(Input::get('csrf'))) {
    $type = Input::get('type');
    $valeur = trim(Input::get('valeur'));
    $file = $_FILES['image'] ?? null;

    if ($type && $valeur && $file && $file['tmp_name']) {
        $filename = uniqid() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);


        if (!is_dir($uploadDir . 'thumbs')) {
        mkdir($uploadDir . 'thumbs', 0755, true);
    }
    $sourcePath = $uploadDir . $filename;
    $thumbPath = $uploadDir . 'thumbs/' . $filename;

        creerMiniature($sourcePath, $thumbPath);
        $db->insert('produits_images', [
            'type' => $type,
            'valeur' => $valeur,
            'fichier' => $filename
        ]);
        $messages[] = "Image ajoutée.";
    } else {
        $messages[] = "Tous les champs sont requis.";
    }



}

function creerMiniature($source, $destination, $largeurMax = 120) {
    $info = getimagesize($source);
    [$largeur, $hauteur] = $info;
    $mime = $info['mime'];

    $ratio = $largeurMax / $largeur;
    $newWidth = $largeurMax;
    $newHeight = $hauteur * $ratio;

    switch ($mime) {
        case 'image/jpeg':
            $srcImg = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $srcImg = imagecreatefrompng($source);
            break;
        case 'image/webp':
            $srcImg = imagecreatefromwebp($source);
            break;
        default:
            return; // type non supporté
    }
    $newWidth = (int)round($newWidth);
    $newHeight = (int)round($newHeight);
    $largeur = (int)round($largeur);
    $hauteur = (int)round($hauteur);
    $thumb = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($thumb, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $largeur, $hauteur);
    imagejpeg($thumb, $destination, 90);
    imagedestroy($thumb);
    imagedestroy($srcImg);
}
$images = $db->query("SELECT * FROM produits_images ORDER BY type, valeur")->results();
$types = ['categorie', 'marque', 'modele', 'version', 'couleur', 'entrepot'];
?>

<div class="container py-4">
  <h3>Gestion des images de filtres</h3>

  <?php if (!empty($messages)): ?>
    <div class="alert alert-info">
      <ul><?php foreach ($messages as $m) echo "<li>$m</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="mb-4">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <input type="hidden" name="ajouter" value="1">
    <div class="row g-2">
      <div class="col-md-2">
        <select name="type" class="form-control" required>
          <option value="">Type</option>
          <?php foreach ($types as $t): ?>
            <option value="<?= $t ?>"><?= ucfirst($t) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <input name="valeur" class="form-control" placeholder="Valeur" required>
      </div>
      <div class="col-md-4">
        <input type="file" name="image" class="form-control" required>
      </div>
      <div class="col-md-3">
        <button class="btn btn-success w-100">Ajouter</button>
      </div>
    </div>
  </form>

  <table class="table table-bordered table-sm">
    <thead><tr><th>Type</th><th>Valeur</th><th>Image</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($images as $img): ?>
        <tr>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="update_id" value="<?= $img->id ?>">
            <td>
              <select name="type" class="form-control form-control-sm">
                <?php foreach ($types as $t): ?>
                  <option value="<?= $t ?>" <?= $img->type === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td><input name="valeur" class="form-control form-control-sm" value="<?= htmlspecialchars($img->valeur) ?>"></td>
            <td>
              <img src="<?= $uploadDir . 'thumbs/' . $img->fichier ?>" alt="miniature" width="80">
              <input type="file" name="nouvelle_image" class="form-control form-control-sm mt-1">
            </td>
            <td>
              <button class="btn btn-sm btn-primary">Modifier</button>
              <a href="?delete=<?= $img->id ?>&csrf=<?= $csrf ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
          </form>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
