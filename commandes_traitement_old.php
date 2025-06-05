<?php

require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) { die(); }
$searchTerm = Input::get('search');
$mode = Input::get('mode');

if (!empty($_GET['mode'])) {

	} else {
	}
if (!empty($_GET['search'])) {
	$query = $db->query("SELECT * FROM commandes WHERE status_commande = ? AND (id = ? OR Nom_revendeur = ?)",['En Attente de traitement',$searchTerm,$searchTerm]);

} else {
    $query = $db->query("SELECT * FROM commandes WHERE status_commande = ?", ['En Attente de traitement']);
}

$count = $query->count();
$results = $query->results();
?>

<div class="row">
    <div class="col-sm-12">
        <h1 style="color:red;">Traitement de commandes</h1>
        <h3>Rechercher Une commande</h3>
        <form action="" method="get">
            <label for="">Entrez Le Numéro De Commande ou le Nom du Revendeur</label>
            <div class="input-group">
                <input class="form-control" type="text" name="search" value="<?=htmlspecialchars($searchTerm ?? '')?>" autofocus placeholder="Search Here!">
                <input class="btn btn-success" type="submit" name="submit" value="Go!">
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br>
        <h2>Vous avez <?=$count?> Commande<?=($count != 1) ? "s" : ""?></h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Revendeur</th>
										<th>Non client</th>
										<th>Prenom client</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Version</th>
                    <th>Couleur</th>
                    <th>Entrepôt</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?=htmlspecialchars($r->id)?></td>
                        <td><?=htmlspecialchars($r->date_commande)?></td>
                        <td><?=htmlspecialchars($r->Nom_revendeur)?></td>
												<td><?=htmlspecialchars($r->nom_acheteur)?></td>
												<td><?=htmlspecialchars($r->prenom_acheteur)?></td>
                        <td><?=htmlspecialchars($r->marque)?></td>
                        <td><?=htmlspecialchars($r->modele)?></td>
                        <td><?=htmlspecialchars($r->version)?></td>
                        <td><?=htmlspecialchars($r->couleur)?></td>
                        <td><?=htmlspecialchars($r->entrepot)?></td>
                        <td><?=htmlspecialchars($r->status_commande)?></td>
                        <td>
													<a href="commandes_traitement.php?num_commande=<?=$r->id?>&mode="1"">Traiter</a>  <!--" Suprimer L'affectation "-->
												</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
