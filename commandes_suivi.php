<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';


if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

// Vérifie que l'utilisateur est connecté
//if (!is_logged_in()) {
//    Redirect::to($us_url_root . 'users/login.php');
//}

// Récupère les commandes du revendeur connecté
$db = DB::getInstance();
$user_id = $user->data()->id;

$commandes = $db->query("SELECT c.*, p.categorie, p.marque, p.modele, p.version, p.couleur, p.entrepot
    FROM commandes c
    JOIN produits p ON c.produit_id = p.id
    WHERE c.id_client_revendeur = ?
    ORDER BY c.date_commande DESC", [$user_id])->results();
?>

<div class="container">
    <h2>Suivi de mes commandes</h2>

    <?php if (empty($commandes)) : ?>
        <p>Vous n'avez encore passé aucune commande.</p>
    <?php else : ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Entrepôt</th>
                    <th>Client final</th>
                    <th>Status</th>
                    <th>Date de réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $cmd) : ?>
                    <tr>
                        <td><?= $cmd->date_commande ?></td>
                        <td><?= "{$cmd->categorie} {$cmd->marque} {$cmd->modele} {$cmd->version} {$cmd->couleur}" ?></td>
                        <td><?= $cmd->entrepot ?></td>
                        <td><?= "{$cmd->nom_acheteur} {$cmd->prenom_acheteur}" ?></td>
                        <td><?= $cmd->status_commande ?></td>
                        <td><?= $cmd->date_commande ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
