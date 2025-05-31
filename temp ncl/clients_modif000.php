<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

// Initialisation des variables
$mode = 0; // 0 saisie, 1 mode modification
$id_client = isset($_GET['id_client']) ? $_GET['id_client'] : null; // Si id_client est passé en GET, sinon null
$lname = isset($_GET['lname']) ? htmlspecialchars($_GET['lname'], ENT_QUOTES, 'UTF-8') : '';
$fname = isset($_GET['fname']) ? htmlspecialchars($_GET['fname'], ENT_QUOTES, 'UTF-8') : '';
$raison_sociale = isset($_GET['raison_sociale']) ? htmlspecialchars($_GET['raison_sociale'], ENT_QUOTES, 'UTF-8') : '';
$ice = isset($_GET['ice']) ? htmlspecialchars($_GET['ice'], ENT_QUOTES, 'UTF-8') : '';
$habilitation = isset($_GET['habilitation']) ? htmlspecialchars($_GET['habilitation'], ENT_QUOTES, 'UTF-8') : '';
$cin_pass = isset($_GET['cin_pass']) ? htmlspecialchars($_GET['cin_pass'], ENT_QUOTES, 'UTF-8') : '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8') : '';
$tel = isset($_GET['tel']) ? htmlspecialchars($_GET['tel'], ENT_QUOTES, 'UTF-8') : '';
$magasin = isset($_GET['magasin']) ? htmlspecialchars($_GET['magasin'], ENT_QUOTES, 'UTF-8') : '';
$ville = isset($_GET['ville']) ? htmlspecialchars($_GET['ville'], ENT_QUOTES, 'UTF-8') : '';
$date = date("Y/m/d");
$username = $cin_pass; // Le username sera la cin_pass
$searchTerm = isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm'], ENT_QUOTES, 'UTF-8') : ''; // Recherche par mot-clé
$password = "123456";
$prompt_ncl = randomstring(15);

// Définir les permissions selon l'habilitation
$permissions = 1; // Valeur par défaut
if ($habilitation == "Client") {
    $permissions = 6;
}
if ($habilitation == "Utilisateur") {
    $permissions = 1;
}

if (isset($_GET['Ajouter'])) {
    if (empty($cin_pass)) {
        $err = array("Le CIN est obligatoire");
        display_errors($err);
    } else {
        // Protection contre l'injection SQL avec requêtes préparées
        $query = $db->query("SELECT id FROM users WHERE cin_pass = ?",[$cin_pass]);
        $count = $query->count();

        if ($count > 0) {
            // Utilisateur déjà existant
            $err = array("Le CIN existe déjà");
            display_errors($err);
        } else {
            // Insérer le client dans la base de données
            $password_hash = password_hash($password, PASSWORD_BCRYPT); // Utiliser un mot de passe sécurisé
            $db->insert('users', [
                'cin_pass' => $cin_pass,
                'lname' => $lname,
                'fname' => $fname,
                'raison_sociale' => $raison_sociale,
                'ice' => $ice,
                'habilitation' => $habilitation,
                'email' => $email,
                'tel' => $tel,
                'magasin' => $magasin,
                'ville' => $ville,
                'username' => $username,
                'password' => $password_hash, // Le mot de passe est haché
                'permissions' => $permissions,
                'date' => $date,
            ]);

            // Message de succès
            $msg = "Client ajouté avec succès";
            echo $msg;
        }
    }
}

if (isset($id_client)) {
    // Récupérer les informations du client à modifier
    $query = $db->query("SELECT * FROM users WHERE id = ?", [$id_client]);
    $client = $query->first();

    if ($client) {
        // Afficher les informations du client
        $lname = $client->lname;
        $fname = $client->fname;
        $raison_sociale = $client->raison_sociale;
        $ice = $client->ice;
        $habilitation = $client->habilitation;
        $cin_pass = $client->cin_pass;
        $email = $client->email;
        $tel = $client->tel;
        $magasin = $client->magasin;
        $ville = $client->ville;
    }
}

// Fonction pour afficher les erreurs
function display_errors($errors) {
    foreach ($errors as $error) {
        echo "<p class='error'>$error</p>";
    }
}

// Affichage du formulaire de modification ou d'ajout
?>
<form method="get" action="">
    <label for="lname">Nom:</label>
    <input type="text" name="lname" value="<?php echo $lname; ?>" required>

    <label for="fname">Prénom:</label>
    <input type="text" name="fname" value="<?php echo $fname; ?>" required>

    <label for="raison_sociale">Raison Sociale:</label>
    <input type="text" name="raison_sociale" value="<?php echo $raison_sociale; ?>" required>

    <label for="ice">ICE:</label>
    <input type="text" name="ice" value="<?php echo $ice; ?>" required>

    <label for="habilitation">Habilitation:</label>
    <select name="habilitation">
        <option value="Client" <?php echo ($habilitation == 'Client' ? 'selected' : ''); ?>>Client</option>
        <option value="Utilisateur" <?php echo ($habilitation == 'Utilisateur' ? 'selected' : ''); ?>>Utilisateur</option>
    </select>

    <label for="cin_pass">CIN/Pass:</label>
    <input type="text" name="cin_pass" value="<?php echo $cin_pass; ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $email; ?>" required>

    <label for="tel">Téléphone:</label>
    <input type="text" name="tel" value="<?php echo $tel; ?>" required>

    <label for="magasin">Magasin:</label>
    <input type="text" name="magasin" value="<?php echo $magasin; ?>" required>

    <label for="ville">Ville:</label>
    <input type="text" name="ville" value="<?php echo $ville; ?>" required>

    <input type="submit" name="Ajouter" value="Ajouter / Modifier">
</form>
