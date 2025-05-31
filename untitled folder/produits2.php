<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>

	<?php
	$searchTerm1_categorie = Input::get('searchTerm1_categorie');
	$searchTerm2_marque = Input::get('searchTerm2_marque');
	$searchTerm3_model = Input::get('searchTerm3_model');
	$searchTerm4_version = Input::get('searchTerm4_version');
	$searchTerm5_couleur = Input::get('searchTerm5_couleur');
	$searchTerm6_vin = Input::get('searchTerm6_vin');



	 $query = $db->query("SELECT DISTINCT(categorie) FROM produits",[]);
 	 $count = $query->count();
 	 $results = $query->results();
	 ?>
<?php if ($count>0){?>

              <div class="col-sm-12">
					    <h2>Produit: <?php print_r($searchTerm1_categorie);?>/<?php print_r($searchTerm2_marque);?>/<?php print_r($searchTerm3_model);?>/<?php print_r($searchTerm4_version);?>/<?php print_r($searchTerm5_couleur);?>/<?php print_r($searchTerm6_vin);?></h2>
              </div>


				<div class="row">
												  <!-- Categorie -->
												 		<div class="col-xs-2">
												 			<table class="table table-striped">
												 				<thead>
																	<tr>
												            <th><?=$count?> Categorie<?php if($count != 1){ echo "s";}	?></th>
																	</tr>
												 				</thead>
												 				<tbody>
												 					<?php foreach ($results as $r) { ?>
												 						<tr>
												              <td style="background-color:<?php if($searchTerm1_categorie == $r->categorie): echo'#F0F8FF'?><?php else: echo' '?><?php endif;?>"><a href="produits.php?searchTerm1_categorie=<?=$r->categorie?>"><?=$r->categorie?></a></td>
											              </tr>
												 					<?php } ?>
												 				</tbody>
												 			</table>
												      </div>
												<!-- end Categorie -->

												<!-- Marque -->
												<?php
												if ($searchTerm1_categorie != "") {
												  $query2 = $db->query("SELECT DISTINCT(marque), categorie FROM produits WHERE categorie = ?",[$searchTerm1_categorie]);
												  $count2 = $query2->count();
												  $results2 = $query2->results();?>

												  <div class="col-xs-2">
												    <table class="table table-striped">
												      <thead>
																</tr>
												          <th><?=$count2?> Marque<?php if($count2 != 1){ echo "s";}	?></th>
																</tr>
												      </thead>
												      <tbody>
												        <?php foreach ($results2 as $r2) { ?>
												          <tr>
												            <td style="background-color:<?php if($searchTerm2_marque == $r2->marque): echo'#F0F8FF'?><?php else: echo' '?><?php endif;?>"><a href="produits.php?searchTerm1_categorie=<?=$r->categorie?>&searchTerm2_marque=<?=$r2->marque?>"><?=$r2->marque?></a></td>
												          </tr>
												        <?php } ?>
												      </tbody>
												    </table>
												    </div>
												    <?php } ?>
												    <!-- end Marque ------------------------------------------------------------------->

				</div>
<?php } ?>


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
