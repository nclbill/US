<?php

// Inclusion des fichiers nécessaires de UserSpice
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }
// Vérifier si l'utilisateur est connecté
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
    exit;
}

//////////////////////////////////////// Recherche /////////////////////////////////////////////////////////////

$mode = Input::get('mode');
$id_sup = Input::get('id_sup');
$searchTerm = Input::get('search');
$id_client_revendeur = $user->data()->id;

// Sanitize et valider l'input
$searchTerm = htmlspecialchars(trim($searchTerm)); // Protection contre XSS

if (!empty($searchTerm)) {
    // Requête sécurisée avec paramètres
		$query = $db->query("SELECT * FROM commandes WHERE id_client_revendeur = ? AND (id = ? OR nom_acheteur = ?)",[$id_client_revendeur,$searchTerm,$searchTerm]);
    $count = $query->count();
    $results = $query->results();
} else {
    $query = $db->query("SELECT * FROM commandes WHERE status_commande = ? AND id_client_revendeur = ?", ['En Attente de traitement', $id_client_revendeur]);
    $count = $query->count();
    $results = $query->results();
}

// Suppression d'une commande avec protection CSRF
if ($mode == 1 && isset($id_sup) && is_numeric($id_sup)) {
    // Vérification du token CSRF
    $csrf_token = Input::get('csrf_token');
    if (!Token::check($csrf_token)) {
        die("Token invalide. Action non autorisée.");
    }

    // Exécuter la suppression de la commande
    $db->query("DELETE FROM commandes WHERE id = ?", [$id_sup]);
    Redirect::to("commandes_suivi.php");
}

?>

<div class="row">
    <!-- Colonne droite -->
    <div class="col-sm-12">
        <h1 style="color:red;">Suivi des commandes</h1>
        <h3>Rechercher une commande</h3>
        <form class="form-inline" action="" method="get">
            <label for="search">Entrez le numéro de commande ou le nom du client</label>
            <div class="input-group">
                <input class="form-control" type="text" name="search" value="<?=$searchTerm?>" autofocus="on" placeholder="Search Here!">
                <input class="btn btn-success" type="submit" name="submit" value="Go!">
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br>
        <h2>Vous avez <?=$count?> commande<?php if ($count != 1) { echo "s"; } ?></h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Revendeur</th>
                    <th>Nom client</th>
                    <th>Prénom client</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Version</th>
                    <th>Couleur</th>
                    <th>Entrepôt</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r) { ?>
                    <tr>
                        <td><?=$r->id?></td>
                        <td><?=$r->date_commande?></td>
                        <td><?=$r->Nom_revendeur?></td>
                        <td><?=$r->nom_acheteur?></td>
                        <td><?=$r->prenom_acheteur?></td>
                        <td><?=$r->marque?></td>
                        <td><?=$r->modele?></td>
                        <td><?=$r->version?></td>
                        <td><?=$r->couleur?></td>
                        <td><?=$r->entrepot?></td>
                        <td><?=$r->status_commande?></td>
                        <td>
                            <!-- Lien de suppression avec protection CSRF -->
                            <a href="commandes_suivi.php?mode=1&id_sup=<?=$r->id?>&csrf_token=<?=Token::generate()?>" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande?');">Annuler</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
