<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>

<?php


$searchTerm1_categorie = Input::get('searchTerm1_categorie');
$searchTerm2_marque = Input::get('searchTerm2_marque');
$searchTerm3_modele= Input::get('searchTerm3_modele');
$searchTerm4_version = Input::get('searchTerm4_version');
$searchTerm5_couleur = Input::get('searchTerm5_couleur');
$searchTerm6_vin = Input::get('searchTerm6_vin');
$entrepot = Input::get('entrepot');

$nom_acheteur = Input::get('nom_acheteur');
$prenom_acheteur = Input::get('prenom_acheteur');
$tel_acheteur = Input::get('tel_acheteur');
$ville_acheteur = Input::get('ville_acheteur');
$cin_pass_recto_acheteur = Input::get('cin_pass_recto_acheteur');
$cin_pass_verso_acheteur = Input::get('cin_pass_verso_acheteur');

$file = "fild_cin";
$fild = "cin_pass";

?>

<?php if (!empty($file)): ?>
  <h2>MODIFIER <?php echo $file; ?></h2>
  <form action="upload.php" method="post" enctype="multipart/form-data">
      Select Image File to Upload:
      <input type="file" name="file">
      <input type="hidden" name="fild" value="<?php echo $fild; ?>">
      <input type="hidden" name="searchTerm1_categorie" value="<?php echo $searchTerm1_categorie; ?>">
      <input type="hidden" name="searchTerm2_marque" value="<?php echo $searchTerm2_marque; ?>">
      <input type="hidden" name="searchTerm3_modele" value="<?php echo $searchTerm3_modele; ?>">
      <input type="hidden" name="searchTerm4_version" value="<?php echo $searchTerm4_version; ?>">
      <input type="hidden" name="searchTerm5_couleur" value="<?php echo $searchTerm5_couleur; ?>">
      <input type="hidden" name="searchTerm6_vin" value="<?php echo $searchTerm6_vin; ?>">
      <input type="hidden" name="entrepot" value="<?php echo $entrepot; ?>">
      <input type="hidden" name="nom_acheteur" value="<?php echo $nom_acheteur; ?>">
      <input type="hidden" name="prenom_acheteur" value="<?php echo $prenom_acheteur; ?>">
      <input type="hidden" name="tel_acheteur" value="<?php echo $tel_acheteur; ?>">
      <input type="hidden" name="ville_acheteur" value="<?php echo $ville_acheteur; ?>">
      <input type="hidden" name="cin_pass_recto_acheteur" value="<?php echo $cin_pass_recto_acheteur; ?>">
      <input type="hidden" name="cin_pass_verso_acheteur" value="<?php echo $cin_pass_verso_acheteur; ?>">


      <input type="submit" name="submit" value="Upload">
  </form>

<?php else: ?>
  <h1>Telecherger</h1>
  <form action="upload.php" method="post" enctype="multipart/form-data">
      Select Image File to Upload:
      <input type="file" name="file">
      <input type="hidden" name="fild" value="<?php echo $fild; ?>">
      <input type="hidden" name="searchTerm1_categorie" value="<?php echo $searchTerm1_categorie; ?>">
      <input type="hidden" name="searchTerm2_marque" value="<?php echo $searchTerm2_marque; ?>">
      <input type="hidden" name="searchTerm3_modele" value="<?php echo $searchTerm3_modele; ?>">
      <input type="hidden" name="searchTerm4_version" value="<?php echo $searchTerm4_version; ?>">
      <input type="hidden" name="searchTerm5_couleur" value="<?php echo $searchTerm5_couleur; ?>">
      <input type="hidden" name="searchTerm6_vin" value="<?php echo $searchTerm6_vin; ?>">
      <input type="hidden" name="entrepot" value="<?php echo $entrepot; ?>">
      <input type="hidden" name="nom_acheteur" value="<?php echo $nom_acheteur; ?>">
      <input type="hidden" name="prenom_acheteur" value="<?php echo $prenom_acheteur; ?>">
      <input type="hidden" name="tel_acheteur" value="<?php echo $tel_acheteur; ?>">
      <input type="hidden" name="ville_acheteur" value="<?php echo $ville_acheteur; ?>">
      <input type="hidden" name="cin_pass_recto_acheteur" value="<?php echo $cin_pass_recto_acheteur; ?>">
      <input type="hidden" name="cin_pass_verso_acheteur" value="<?php echo $cin_pass_verso_acheteur; ?>">
      <input type="submit" name="submit" value="Upload">
  </form>
<?php endif; ?>





<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
