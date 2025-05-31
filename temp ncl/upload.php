<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

// Récupération et sécurisation des données
$categorie = htmlspecialchars(Input::get('searchTerm1_categorie'));
$marque = htmlspecialchars(Input::get('searchTerm2_marque'));
$modele = htmlspecialchars(Input::get('searchTerm3_modele'));
$version = htmlspecialchars(Input::get('searchTerm4_version'));
$couleur = htmlspecialchars(Input::get('searchTerm5_couleur'));
$entrepot = htmlspecialchars(Input::get('entrepot'));

$nom_acheteur = htmlspecialchars(Input::get('nom_acheteur'));
$prenom_acheteur = htmlspecialchars(Input::get('prenom_acheteur'));
$tel_acheteur = htmlspecialchars(Input::get('tel_acheteur'));
$ville_acheteur = htmlspecialchars(Input::get('ville_acheteur'));

$id_client_revendeur = $user->data()->id;
$Nom_revendeur = $user->data()->lname;

$statusMsg = '';
$targetDir = "uploads/";
$allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'docx', 'pdf');
$maxFileSize = 5 * 1024 * 1024; // 5 MB

// Vérification et traitement des fichiers
$uploadedFiles = [];
foreach (['cin_pass_recto_acheteur', 'cin_pass_verso_acheteur'] as $fileField) {
    if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == 0) {
        $fileName = str_replace(' ', '_', basename($_FILES[$fileField]['name']));
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileSize = $_FILES[$fileField]['size'];

        // Vérification de la taille du fichier
        if ($fileSize > $maxFileSize) {
            $statusMsg = "Le fichier est trop volumineux pour : " . $fileField;
            echo "<p>$statusMsg</p>";
            exit;
        }

        // Vérification du type de fichier
        if (in_array(strtolower($fileType), $allowTypes)) {
            $targetFilePath = $targetDir . time() . "_" . uniqid() . "_" . $fileName;

            // Déplacement du fichier vers le dossier de destination
            if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $targetFilePath)) {
                $uploadedFiles[$fileField] = $targetFilePath;
            } else {
                $statusMsg = "Erreur lors du téléchargement du fichier : " . $fileField;
                echo "<p>$statusMsg</p>";
                exit;
            }
        } else {
            $statusMsg = "Type de fichier non autorisé pour : " . $fileField;
            echo "<p>$statusMsg</p>";
            exit;
        }
    } else {
        $statusMsg = "Aucun fichier sélectionné pour : " . $fileField;
        echo "<p>$statusMsg</p>";
        exit;
    }
}

// Insertion des données dans la base
try {
    $fields_commande = array(
        "categorie" => $categorie,
    //    "marque" => $marque,
    //    "modele" => $modele,
    //    "version" => $version,
        "couleur" => $couleur,
    //    "entrepot" => $entrepot,
    //    "id_client_revendeur" => $id_client_revendeur,
    //    "Nom_revendeur" => $Nom_revendeur,
    //    "nom_acheteur" => $nom_acheteur,
    //    "prenom_acheteur" => $prenom_acheteur,
    //    "tel_acheteur" => $tel_acheteur,
    //    "ville_acheteur" => $ville_acheteur,
    //    "cin_pass_recto_acheteur" => $uploadedFiles['cin_pass_recto_acheteur'] ?? null,
    //    "cin_pass_verso_acheteur" => $uploadedFiles['cin_pass_verso_acheteur'] ?? null,
    );

    // Requête préparée pour l'insertion
    $db->insert("commandes", $fields_commande);
    $statusMsg = "Commande enregistrée avec succès et fichiers uploadés.";
} catch (Exception $e) {
    $statusMsg = "Échec de l'insertion dans la base de données : " . $e->getMessage();
}

// Affichage du message de confirmation
echo "<p>$statusMsg</p>";

require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php';
?>
