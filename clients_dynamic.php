<?php

// Inclusion des fichiers nécessaires à l'initialisation et à la préparation de la page
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

// Vérification de la sécurité de la page
if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}
if(hasPerm([2,3,5],$user->data()->id)){$mode = 4;}// // 1 user 4 saisie 5 controleur mode modification}// 2 admin 3 gestionaire 6 client  7 commercial
if(hasPerm([4],$user->data()->id)){$mode = 3;}// // 1 user 4 saisie 5 controleur mode modification}// 2 admin 3 gestionaire 6 client  7 commercial

?>

<?php
//$mode = 0; // 0 pour saisie, 1 pour modification
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
$searchTerm = isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm'], ENT_QUOTES, 'UTF-8') : ''; // Recherche par mot-clé
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; // Recherche par mot-clé

$prompt_ncl = randomstring(15);
$password = randomstring(6);
$username=$cin_pass;

// Définition des permissions en fonction du rôle
if ($habilitation == "Client") {
    $permissions = 6;
}
if ($habilitation == "Utilisateur") {
    $permissions = 1;
}

// Traitement lors de l'ajout d'un client
if(!empty($_GET['Ajouter'])) {
    // Vérification des champs obligatoires
    if (empty($cin_pass)) {
        $err = array("CIN Obligatoire");
        display_errors($err);
    } else {
        // Vérification si le CIN existe déjà
        $query = $db->query("SELECT id FROM users WHERE cin_pass = ? ",[$cin_pass]);
        $count = $query->count();
        if($count >= 1){
            $err = array("CIN existe déjà parmi vos utilisateurs");
            display_errors($err);
        } else {
            // Vérification des autres champs obligatoires
            if (empty($email)){
                $err = array("Email Obligatoire");
                display_errors($err);
            } else if (empty($lname)){
                $err = array("Nom Obligatoire");
                display_errors($err);
            } else if (empty($fname)){
                $err = array("Prénom Obligatoire");
                display_errors($err);
            } else if (empty($raison_sociale)){
                $err = array("Raison sociale Obligatoire");
                display_errors($err);
            } else if (empty($ice)){
                $err = array("ICE Obligatoire");
                display_errors($err);
            } else if (empty($tel)){
                $err = array("Téléphone Obligatoire");
                display_errors($err);
            } else if (empty($magasin)){
                $err = array("Magasin Obligatoire");
                display_errors($err);
            } else if (empty($ville)){
                $err = array("Ville Obligatoire");
                display_errors($err);
            } else if (empty($habilitation)){
                $err = array("Habilitation Obligatoire");
                display_errors($err);
            } else {
                // Génération d'un code de vérification et d'un mot de passe aléatoire
                $prompt_ncl = randomstring(15);

                // Insertion des données dans la base
                $fields1 = array(
                    "permissions" => $permissions,
                    "email" => $email,
                    "username" => $username,
                    "fname" => $fname,
                    "lname" => $lname,
                    "raison_sociale" => $raison_sociale,
                    "ice" => $ice,
                    "cin_pass" => $cin_pass,
                    "tel" => $tel,
                    "habilitation" => $habilitation,
                    "vericode" => 0,
                    "vericode_expiry" => $password,
                    "oauth_tos_accepted" => true,
                    "email_verified" => 1,
                    "account_owner" => 1,
                    "join_date" => $date,
                    "active" => 1,
                    "ville" => $ville,
                    "magasin" => $magasin,
                    "prompt_ncl" => $prompt_ncl,
                    "password" => password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)),
                );

                // Insertion dans la table "users"
                $db->insert("users", $fields1);

                // Récupération de l'utilisateur inséré
                $query = $db->query("SELECT * FROM users WHERE prompt_ncl = ?",[$prompt_ncl]);
                $results = $query->results();


                $id_user = $results[0]->id;
                $username = $results[0]->username;
                $email = $results[0]->email;

                // Insertion dans la table des permissions
                $db->insert("user_permission_matches", ["user_id" => $id_user, "permission_id" => $permissions]);
                $db->insert("user_permission_matches", ["user_id" => $id_user, "permission_id" => 1]);

                // Suppression du "prompt_ncl"
                $db->update("users", $id_user, ["prompt_ncl" => 0]);






                // Envoi de l'email de confirmation avec le login et le mot de passe
                $subject = "Votre mot de passe @EspaceMoto";
                $body = "Login: " . $username . " / Mot de passe: " . $password;
                email($email, $subject, $body);
///oooooooooooooooooooooooooooooooooooooooooooooPOUR DEV A SUPRIMER///oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                email("nclteck@gmail.com",$subject, $body);
///ooooooooooooooooooooooooooooooooooooooooooooPOUR DEV A SUPRIMER///oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo


                // Redirection après ajout
                Redirect::to("clients.php");
              //  logger($user->data()->id, "Ajout Article", "a ajouté $nom $code_interne");
            }
        }
    }
}
//////////////////////////////////////// fin ajout /////////////////////////////////////////////////////////////
//////////////////////////////////////// Recherche /////////////////////////////////////////////////////////////


//$results_client = [];
//$count_client = 0;

if(Input::exists('get') && !empty(Input::get('search'))) {
    $searchTerm = trim(Input::get('search'));
    $search = '%' . $searchTerm . '%';

    $query_client = $db->query("
        SELECT * FROM users
        WHERE permissions IN (1, 6) AND (
            cin_pass LIKE ?
            OR email LIKE ?
            OR username LIKE ?
            OR fname LIKE ?
            OR lname LIKE ?
            OR raison_sociale LIKE ?
        )

    ", [
        $search, $search, $search, $search, $search, $search
    ]);

//$query_client = $db->query("SELECT * FROM users WHERE cin_pass = ? and permissions = ? or cin_pass = ? and permissions = ?",[$searchTerm, 1, $searchTerm, 6]);
$count_client = $query_client->count();
$results_client = $query_client->results();

// Log de la recherche
//logger($user->data()->id, "Recherche article", "Recherche pour $searchTerm");

// Remplissage des informations si un client est trouvé
if ($count_client > 0) {
    $mode = 1; // Mode modification
    $id_client = $results_client[0]->id;
    $cin_pass = $results_client[0]->cin_pass;
    $lname = $results_client[0]->lname;
    $fname = $results_client[0]->fname;
    $raison_sociale = $results_client[0]->raison_sociale;
    $ice = $results_client[0]->ice;
    $habilitation = $results_client[0]->habilitation;
    $magasin = $results_client[0]->magasin;
    $email = $results_client[0]->email;
    $tel = $results_client[0]->tel;
    $ville = $results_client[0]->ville;
}




}
if ($mode==4) {

//////////////////////////////////////// fin  Recherche /////////////////////////////////////////////////////////////
//////////////////////////////////////// Supprimer /////////////////////////////////////////////////////////////

if(!empty($_GET['Supprimer'])){
    $id_user = Input::get('id_client');
    // Suppression des permissions et de l'utilisateur
    $query_permission = $db->query("SELECT * FROM user_permission_matches WHERE user_id = ?",[$id_user]);
    $results_permission = $query_permission->results();
    $id_permission = $results_permission[0]->id; // Habilitation
    $id_permission2 = $results_permission[1]->id; // Utilisateur public

    // Suppression des permissions et de l'utilisateur
    $db->query("DELETE FROM user_permission_matches WHERE id = ?",[$id_permission]);
    $db->query("DELETE FROM user_permission_matches WHERE id = ?",[$id_permission2]);
    $db->query("DELETE FROM users WHERE id = ?",[$id_user]);

    $mode = 1; // Mode modification
    Redirect::to("clients.php");
}
//////////////////////////////////////// fin suprimer /////////////////////////////////////////////////////////////
//////////////////////////////////////// Modifier /////////////////////////////////////////////////////////////

if(!empty($_GET['Modifier'])){
    $id_client = Input::get('id_client');
    $searchTerm = Input::get('searchTerm');
    $lname = Input::get('lname');
    $fname = Input::get('fname');
    $raison_sociale = Input::get('raison_sociale');
    $ice = Input::get('ice');
    $habilitation = Input::get('habilitation');
    $cin_pass = Input::get('cin_pass');
    $magasin = Input::get('magasin');
    $date = date("Y/m/d");
    $mode = 1;
    $email = Input::get('email');
    $ville = Input::get('ville');
    $tel = Input::get('tel');
    $username = $cin_pass;

    // Définition des permissions selon l'habilitation
    if ($habilitation == "Client") {
        $permissions = 6;
    }
    if ($habilitation == "Utilisateur") {
        $permissions = 1;
    }

    // Vérification des champs et mise à jour des données
    if (empty($cin_pass)) {
        $err = array("CIN Obligatoire");
        display_errors($err);
    } else {
        // Vérification que le CIN n'existe pas déjà
        $query = $db->query("SELECT id FROM users WHERE cin_pass = ? and permissions <> ? or cin_pass = ?",[$cin_pass, 6, $cin_pass, 1]);
        $count = $query->count();
        if($count >= 1){
            $err = array("CIN ou PASSEPORT d'un collaborateur, votre habilitation ne vous permet pas de modifier des profils collaborateurs");
            display_errors($err);
        } else {
            // Mise à jour des données
            if (empty($email)){
                $err = array("Email Obligatoire");
                display_errors($err);
            } else if (empty($lname)){
                $err = array("Nom Obligatoire");
                display_errors($err);
            } else if (empty($fname)){
                $err = array("Prénom Obligatoire");
                display_errors($err);
            } else if (empty($raison_sociale)){
                $err = array("Raison sociale Obligatoire");
                display_errors($err);
            } else if (empty($ice)){
                $err = array("ICE Obligatoire");
                display_errors($err);
            } else if (empty($tel)){
                $err = array("Téléphone Obligatoire");
                display_errors($err);
            } else if (empty($magasin)){
                $err = array("Magasin Obligatoire");
                display_errors($err);
            } else if (empty($ville)){
                $err = array("Ville Obligatoire");
                display_errors($err);
            } else if (empty($habilitation)){
                $err = array("Habilitation Obligatoire");
                display_errors($err);
            } else {
                // Récupération des informations client et permissions
              //  $query_client = $db->query("SELECT * FROM users WHERE cin_pass = ? and permissions = ? or cin_pass = ? and permissions = ?",[$searchTerm, 1, $searchTerm, 6]);
              //  $count_client = $query_client->count();
              //  $results_client = $query_client->results();
              //  $id_user = $results_client[0]->id;
              $id_user = Input::get('id_client');
                // Mise à jour des informations de l'utilisateur
                $fields = [
                    'permissions' => $permissions,
                    'email' => $email,
                    'username' => $username,
                    'fname' => $fname,
                    'lname' => $lname,
                    'raison_sociale' => $raison_sociale,
                    'ice' => $ice,
                    'cin_pass' => $cin_pass,
                    'tel' => $tel,
                    'habilitation' => $habilitation,
                    'magasin' => $magasin,
                    'ville' => $ville,
                ];
                $db->update('users', $id_user, $fields);
                Redirect::to("clients.php");
            }
        }
    }
}
}////////////////////////////////////// fin modifier



/////////////////////////// selected_client
$query_selected_client = $db->query("SELECT * FROM users  WHERE  id = ?",[$id_client]);
$count_selected_client = $query_selected_client->count();
$results_selected_client = $query_selected_client->results();

if ($count_selected_client > 0) {

    $id_client = $results_selected_client[0]->id;
    $cin_pass_selected_client = $results_selected_client[0]->cin_pass;
    $lname_selected_client = $results_selected_client[0]->lname;
    $fname_selected_client = $results_selected_client[0]->fname;
    $raison_sociale_selected_client = $results_selected_client[0]->raison_sociale;
    $ice_selected_client = $results_selected_client[0]->ice;
    $habilitation_selected_client = $results_selected_client[0]->habilitation;
    $magasin_selected_client = $results_selected_client[0]->magasin;
    $email_selected_client = $results_selected_client[0]->email;
    $tel_selected_client = $results_selected_client[0]->tel;
    $ville_selected_client = $results_selected_client[0]->ville;
}

?>

<!-- Formulaire HTML pour la gestion des clients -->
<div class="row">
    <div class="col-sm-12">
        <h2 align="center">Client</h2>
        <p align="center">
            <form action="" method="get">
                <!-- Champs du formulaire -->
                <label for="cin_pass">CIN/Passeport</label>
                <input class="form-control" type="text" name="cin_pass" value="<?= $cin_pass_selected_client ?>" placeholder="CIN/Passeport">

                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" value="<?= $email_selected_client ?>" placeholder="Email">

                <label for="lname">Nom</label>
                <input class="form-control" type="text" name="lname" value="<?= $lname_selected_client ?>" placeholder="Nom">

                <label for="fname">Prénom</label>
                <input class="form-control" type="text" name="fname" value="<?= $fname_selected_client ?>" placeholder="Prénom">

                <label for="raison_sociale">Raison Sociale</label>
                <input class="form-control" type="text" name="raison_sociale" value="<?= $raison_sociale_selected_client ?>" placeholder="Raison Sociale">

                <label for="ice">ICE</label>
                <input class="form-control" type="text" name="ice" value="<?= $ice_selected_client ?>" placeholder="ICE">

                <label for="tel">Téléphone</label>
                <input class="form-control" type="text" name="tel" value="<?= $tel_selected_client ?>" placeholder="Téléphone">

                <label for="magasin">Magasin</label>
                <input class="form-control" type="text" name="magasin" value="<?= $magasin_selected_client ?>" placeholder="Magasin">

                <label for="ville">Ville</label>
                <input class="form-control" type="text" name="ville" value="<?= $ville_selected_client ?>" placeholder="Ville">

                <label for="habilitation">Habilitation</label>
                <select name="habilitation" class="form-control">
                    <option value="<?= $habilitation_selected_client ?>"><?= $habilitation_selected_client ?></option>
                    <option value="Utilisateur">Utilisateur</option>
                    <option value="Client">Client</option>
                </select>

                <input type="hidden" name="id_client" value="<?= $id_client ?>">
                <input type="hidden" name="searchTerm" value="<?= $searchTerm ?>">

                <!-- Boutons de soumission -->
                <input class="btn btn-success" type="submit" name="Ajouter" value="Ajouter">
                <?php if ($mode == 4): ?>
                    <input class="btn btn-success" type="submit" name="Modifier" value="Modifier">
                    <input class="btn btn-danger" type="submit" name="Supprimer" value="Supprimer">
                <?php endif; ?>
            </form>
        </p>
    </div>

    <div class="row">
      <!-- left col -->

      <!-- right col -->
      <div  class="col-sm-12">
        <h2>Rechercher Un client</h2>
        <form class="" action="" method="get">
          <label for="">Entez La CIN ou le Passport</label>
          <div class="input-group">
          <input class="form-control" type="hidden" name="id_client" value="<?=$id_client?>" required  autofocus="on" placeholder="id_client">
          <input class="form-control" type="text" name="search" value="<?=$searchTerm?>"required autofocus="on" placeholder="Search Here!">
          <input class="btn btn-success" type="submit" name="submit" value="Go!">

          </div>
        </form>
      </div>
    </div>
    <?php

  /*	 */?>

  <?php
  if(!empty($_GET['search'])){



          $query_all_client = $query_client;


    //      $query_all_client = $db->query("SELECT * FROM users  WHERE  permissions = ? or permissions = ?",[1,6]);
          $count_all_client = $query_all_client->count();
          $results_all_client = $query_all_client->results();
        //	$id_client = $results_client[0]->id;
        //	$id_user = $results_client[0]->id_user;

  //print_r($results_all_client);
        ?>


      <div class="row">
        <div class="col-sm-12">
          <br>
          <h2>vous avez <?=$count_all_client?> client<?php if($count_all_client != 1){ echo "s";}	?>
          </h2>

          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Prenom</th>
                <th>nom</th>
                <th>R Sociale</th>
                <th>ICE</th>
                <th>cin_pass</th>
                <th>tel</th>
                <th>Email</th>
                <th>Fonction</th>
                <th>magasin</th>
                <th>ville</th>


              </tr>
            </thead>
            <tbody>
              <?php foreach ($results_all_client as $r) { ?>
                <tr>
                  <td><?=$r->id?></td>
                  <td><?=$r->fname?></td>
                  <td><?=$r->lname?></td>
                  <td><?=$r->raison_sociale?></td>
                  <td><?=$r->ice?></td>
                  <td><?=$r->cin_pass?></td>
                  <td><?=$r->tel?></td>
                  <td><?=$r->email?></td>
                  <td><?=$r->habilitation?></td>
                  <td><?=$r->magasin?></td>
                  <td><?=$r->ville?></td>
                  <td>
                    <a href="clients.php?searchTerm=<?=$searchTerm?>&id_client=<?=$r->id?>">Select</a>
                  </td>
                </tr>
              <?php }  } ?>
            </tbody>
          </table>

        </div>
      </div>


</div>

<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
