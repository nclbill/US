<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }

// Mode par défaut : 0 (ajout), 1 (modification)
$mode = Input::get('mode', 0);

$searchTerm1_categorie = Input::get('searchTerm1_categorie');
$searchTerm2_marque = Input::get('searchTerm2_marque');
$searchTerm3_modele = Input::get('searchTerm3_modele');
$searchTerm4_version = Input::get('searchTerm4_version');
$searchTerm5_couleur = Input::get('searchTerm5_couleur');
$searchTerm6_vin = Input::get('searchTerm6_vin');

$id_client = $user->data()->id;


// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = Input::get('action');
    $categorie = Input::get('categorie');
    $marque = Input::get('marque');
    $modele = Input::get('modele');
    $version = Input::get('version');
    $couleur = Input::get('couleur');
    $vin = Input::get('vin');

    // Ajouter un produit
    if ($action == 'ajouter') {
        $db->query("INSERT INTO produits (categorie, marque, modele, version, couleur, vin) VALUES (?, ?, ?, ?, ?, ?)", [
            $categorie, $marque, $modele, $version, $couleur, $vin
        ]);
    }
    // Modifier un produit
    elseif ($action == 'modifier' && !empty($vin)) {
        $db->query("UPDATE produits SET categorie = ?, marque = ?, modele = ?, version = ?, couleur = ? WHERE vin  = ?", [
            $categorie, $marque, $modele, $version, $couleur, $vin
        ]);
    }
    // Supprimer un produit
    elseif ($action == 'supprimer' && !empty($vin)) {
        $db->query("DELETE FROM produits WHERE vin = ?", [$vin]);
    }
}


// Récupération des catégories
$query = $db->query("SELECT DISTINCT categorie FROM produits", []);
$count = $query->count();
$results = $query->results();
?>

<div class="container">
    <h2>Article: <?= htmlspecialchars("$searchTerm1_categorie / $searchTerm2_marque / $searchTerm3_modele / $searchTerm4_version / $searchTerm5_couleur / $searchTerm6_vin") ?></h2>

    <div class="row">
        <!-- COLONNE CATÉGORIES -->
        <div class="col-auto">
            <table class="table table-bordered">
                <thead><tr><th>Catégories</th></tr></thead>
                <tbody>
                    <?php foreach ($results as $r) { ?>
                        <tr>
                            <td style="background-color: <?= ($searchTerm1_categorie == $r->categorie) ? '#ADD8e6' : '' ?>">
                                <a href="produits.php?searchTerm1_categorie=<?= urlencode($r->categorie) ?>">
                                    <?= htmlspecialchars($r->categorie) ?>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- COLONNES DYNAMIQUES -->
        <?php
        $filters = [
            "marque" => ["query" => "SELECT DISTINCT marque FROM produits WHERE categorie = ?", "parent" => $searchTerm1_categorie],
            "modele" => ["query" => "SELECT DISTINCT modele FROM produits WHERE marque = ?", "parent" => $searchTerm2_marque],
            "version" => ["query" => "SELECT DISTINCT version FROM produits WHERE modele = ?", "parent" => $searchTerm3_modele],
            "couleur" => ["query" => "SELECT DISTINCT couleur FROM produits WHERE version = ?", "parent" => $searchTerm4_version],
            "vin" => ["query" => "SELECT DISTINCT vin FROM produits WHERE couleur = ?", "parent" => $searchTerm5_couleur]
        ];

        $params = [
            "searchTerm1_categorie" => $searchTerm1_categorie,
            "searchTerm2_marque" => $searchTerm2_marque,
            "searchTerm3_modele" => $searchTerm3_modele,
            "searchTerm4_version" => $searchTerm4_version,
            "searchTerm5_couleur" => $searchTerm5_couleur,
            "searchTerm6_vin" => $searchTerm6_vin
        ];

        $i = 2;
        foreach ($filters as $key => $filter) {
            if (!empty($filter["parent"])) {
                $query = $db->query($filter["query"], [$filter["parent"]]);
                $count = $query->count();
                $results = $query->results();
                ?>
                <div class="col-auto">
                    <table class="table table-bordered">
                        <thead><tr><th><?= ucfirst($key) ?>s</th></tr></thead>
                        <tbody>
                            <?php foreach ($results as $r) { ?>
                                <tr>
                                    <td style="background-color: <?= ($params["searchTerm" . $i . "_" . $key] == $r->$key) ? '#ADD8e6' : '' ?>">
                                        <a href="produits.php?<?= http_build_query(array_merge($params, ["searchTerm" . $i . "_" . $key => $r->$key])) ?>">
                                            <?= htmlspecialchars($r->$key) ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $i++;
            }
        }
        ?>
    </div>

    <!-- FORMULAIRE D'AJOUT / MODIFICATION -->
    <form method="post" action="produits.php">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="id_client" value="<?= $id_client ?>">

        <div class="row">
            <div class="col-md-2">
                <label>Catégorie</label>
                <input type="text" name="categorie" class="form-control" value="<?= $searchTerm1_categorie ?>" required>
            </div>
            <div class="col-md-2">
                <label>Marque</label>
                <input type="text" name="marque" class="form-control" value="<?= $searchTerm2_marque ?>" required>
            </div>
            <div class="col-md-2">
                <label>Modèle</label>
                <input type="text" name="modele" class="form-control" value="<?= $searchTerm3_modele ?>" required>
            </div>
            <div class="col-md-2">
                <label>Version</label>
                <input type="text" name="version" class="form-control" value="<?= $searchTerm4_version ?>" required>
            </div>
            <div class="col-md-2">
                <label>Couleur</label>
                <input type="text" name="couleur" class="form-control" value="<?= $searchTerm5_couleur ?>" required>
            </div>
            <div class="col-md-2">
                <label>VIN</label>
                <input type="text" name="vin" class="form-control" value="<?= $searchTerm6_vin ?>" required>
            </div>
        </div>

        <br>
        <button type="submit" name="action" value="ajouter" class="btn btn-primary">Ajouter</button>
        <button type="submit" name="action" value="modifier" class="btn btn-warning" <?= empty($searchTerm6_vin) ? 'disabled' : '' ?>>Modifier</button>
        <button type="submit" name="action" value="supprimer" class="btn btn-danger" <?= empty($searchTerm6_vin) ? 'disabled' : '' ?>>Supprimer</button>
    </form>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
