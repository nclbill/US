<?php
function checkCommandesExpirees() {
    $db = DB::getInstance();
    $expirationLimit = date("Y-m-d H:i:s", strtotime("-1 hour"));

    // Mise à jour des commandes expirées
    $db->query("UPDATE commandes
                SET status_commande = 'Expiree'
                WHERE status_commande = 'Reserve'
                  AND delai < ?", [$expirationLimit]);

    // Libération des produits liés aux commandes expirées
    $db->query("UPDATE produits
                JOIN commandes ON commandes.produit_id = produits.id
                SET produits.status = 'Disponible'
                WHERE commandes.status_commande = 'Expiree'");
}
