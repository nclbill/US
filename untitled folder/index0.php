<?php
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';


////////////////////////////////////////////////////   style   ///////////////////////////////////////////////////////?>
<style media="screen">
.huge {
		font-size: 50px;
		line-height: normal;
}
a {
    text-decoration: none !important;
}
.card {
	margin-top:1em;
}

</style>
<div id="page-wrapper">
	<div class="container">
		<?php
		if($user->isLoggedIn()){ /////si connecte ?>
		<div class="row">
<?php


$query_id_ncl_cli = $db->query("SELECT * FROM users WHERE id = ?",[$_SESSION["user"]]);
$count_id_ncl_cli = $query_id_ncl_cli->count();
$results_id_ncl_cli = $query_id_ncl_cli->results();

$id_ncl_cli = $results_id_ncl_cli[0]->id_ncl_cli; // id du patron mon client

//$id = $results_id_ncl_cli[0]->id;


								//				////////////////////////////////determination de id_ncl_cli selon les utlisateurs
								//				if ($id_ncl_cli == "0") { ////// si c est un ncl_cli tout les client ncl on dans id_ncl_cli "0" ou moi admin general

								//					$id_ncl_cli=$_SESSION["user"];
								//					$_SESSION["id_ncl_cli"] = $_SESSION["user"];
								//				}else{ // c'est un collaborateur id_ncl_cli = id du client ncl qui lui est ratache
								//				  $_SESSION["id_ncl_cli"] = $id_ncl_cli;
								//				}
											//}
 $_SESSION["id_ncl_cli"] = $id_ncl_cli;



//////////////////////////////////////////////////////////////////////////////// cards admin ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////// cards nouveau ncl client ////////////////////////////////////////////////////////////////////////

?>
<?php if(hasPerm([2],$user->data()->id)){
?>
				<!-- Accepter New Syndics Card-->
				<div class="card col-12 col-sm-6 col-md-4">
					<h3 class="card-header text-center"><strong><a href="/skbt/add_ncl_cli.php">Ajouter client NCL</strong></h3>
						<div class="card-body">
							<h4 class="card-title text-center"><div class="huge"> <i class='fa fa-handshake-o fa-1x'></i></div></h4>
							</div>
						<div class="card-footer">
							<span class="pull-left">Nouveau Client</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						</div>
					</div>
				  	<!-- end Accepter New Syndics -->
<?php }

//////////////////////////////////////////////////////////////////////////////// nouveau collaborateur ////////////////////////////////////////////////////////////////////////
?>
<?php if(!hasPerm([2],$user->data()->id)){// pour ne pas etre afficher chez admin ?>
<?php if(hasPerm([6],$user->data()->id)){  // 6 nclcli ?>

				<!-- -->
				<div class="card col-12 col-sm-6 col-md-4">
					<h3 class="card-header text-center"><strong><a href="/skbt/collaborateur.php">Collaborateurs</strong></h3>
						<div class="card-body">
							<h4 class="card-title text-center"><div class="huge"> <i class='fa fa-handshake-o fa-1x'></i></div></h4>
							</div>
						<div class="card-footer">
							<span class="pull-left">Collaborateurs</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						</div>
					</div>
				  	<!-- end Accepter New Syndics -->




									 <!-- Accepter New Syndics Card-->
									 <div class="card col-12 col-sm-6 col-md-4">
										 <h3 class="card-header text-center"><strong><a href="/skbt/users/admin.php?view=users">tableaux de bord</strong></h3>
											 <div class="card-body">
												 <h4 class="card-title text-center"><div class="huge"> <i class='fa fa-handshake-o fa-1x'></i></div></h4>
												 </div>
											 <div class="card-footer">
												 <span class="pull-left">Nouveau article</span>
												 <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
											 </div>
										 </div>
											 <!-- end Accepter New Syndics -->

<?php }

//////////////////////////////////////////////////////////////////////////////// nouveau produit ////////////////////////////////////////////////////////////////////////

?> <?php if(hasPerm([3,6,5],$user->data()->id)){// 3 saisi  5 modif 6 ncl cli?>
	<?php
	//////////////////////////////////////////////////////////////////////////////// nouveau client ////////////////////////////////////////////////////////////////////////
	 ?>


					<!-- Accepter New Syndics Card-->
					<div class="card col-12 col-sm-6 col-md-4">
						<h3 class="card-header text-center"><strong><a href="/skbt/client.php">Nouveau Client</strong></h3>
							<div class="card-body">
								<h4 class="card-title text-center"><div class="huge"> <i class='fa fa-handshake-o fa-1x'></i></div></h4>
								</div>
							<div class="card-footer">
								<span class="pull-left">Ajouter Un Client</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							</div>
						</div>
					  	<!-- end Accepter New Syndics -->
<?php
//////////////////////////////////////////////////////////////////////////////// nouveau produit ////////////////////////////////////////////////////////////////////////
 ?>
				<!-- Accepter New Syndics Card-->
				<div class="card col-12 col-sm-6 col-md-4">
					<h3 class="card-header text-center"><strong><a href="/skbt/vehicule.php">Vehicule</strong></h3>
						<div class="card-body">
							<h4 class="card-title text-center"><div class="huge"> <i class='fa  fa-motorcycle fa-1x'></i></div></h4>
							</div>
						<div class="card-footer">
							<span class="pull-left">Vehicule</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						</div>
					</div>
				  	<!-- end Accepter New Syndics -->

						<?php
						//////////////////////////////////////////////////////////////////////////////// nouvel arcitle ////////////////////////////////////////////////////////////////////////
						 ?>



										<!-- Accepter New Syndics Card-->
										<div class="card col-12 col-sm-6 col-md-4">
											<h3 class="card-header text-center"><strong><a href="/skbt/article.php">Nouvel Article</strong></h3>
												<div class="card-body">
													<h4 class="card-title text-center"><div class="huge"> <i class='fa f fa-cog fa-1x'></i></div></h4>
													</div>
												<div class="card-footer">
													<span class="pull-left">Nouvel Article</span>
													<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
												</div>
											</div>
										  	<!-- end Accepter New Syndics -->
<?php
//////////////////////////////////////////////////////////////////////////////// entretien ////////////////////////////////////////////////////////////////////////
 ?>



				<!-- Accepter New Syndics Card-->
				<div class="card col-12 col-sm-6 col-md-4">
					<h3 class="card-header text-center"><strong><a href="/skbt/users/admin.php?view=users">Entretient</strong></h3>
						<div class="card-body">
							<h4 class="card-title text-center"><div class="huge"> <i class='fa fa-wrench fa-1x'></i></div></h4>
							</div>
						<div class="card-footer">
							<span class="pull-left">Entretient</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						</div>
					</div>
				  	<!-- end Accepter New Syndics -->




<?php }
//////////////////////////////////////////////////////////////////////////////// modification ////////////////////////////////////////////////////////////////////////
 ?>





<?php } ?>
				 <?php //}/////////////////////////////////////////////////////////////////// fin cards ///////////////////////////////////////////////////////////////////////////	?>

				 <?php

				 ?>
				 <!--
				 <div class="row">
					 <div class="col-sm-12">
						 <br>

						 </h2>

						 <table class="table table-striped">
							 <thead>
								 <tr>
									 <th>ID</th>
									 <th>Prenom</th>
									 <th>nom</th>
									 <th>Cin</th>
									 <th>tel</th>
									 <th>Email</th>
                   <th>id_ncl_cli</th>

								 </tr>
							 </thead>
							 <tbody>
								 <?php /*foreach ($results_id_ncl_cli as $r) { ?>
									 <tr>
										 <td><?=$r->id?></td>
										 <td><?=$r->fname?></td>
										 <td><?=$r->lname?></td>
										 <td><?=$r->cin?></td>
										 <td><?=$r->tel?></td>
										 <td><?=$r->email?></td>
										 <td><?=$r->id_ncl_cli?></td>

									 </tr>
								 <?php }*/ ?>
							 </tbody>
						 </table>

					 </div>
				 </div>

 -->
</div>

				<?php ///////////////////////////////////////////////////////// NOT logged in??????????????????????????
			}else {?>
					<div id="page-wrapper">
						<div class="container">
							<div class="jumbotron">
								<h1 align="center"><?=lang("JOIN_SUC");?> <?php echo $settings->site_name;?></h1>
								<p align="center" class="text-muted"><?=lang("MAINT_OPEN")?></p>
								<p align="center">
					<a class="btn btn-warning" href="users/login.php" role="button"><?=lang("SIGNIN_TEXT");?> &raquo;</a>
					<a class="btn btn-info" href="users/join.php" role="button"><?=lang("SIGNUP_TEXT");?> &raquo;</a>
				</p>
				<br>
				<p align="center"><?=lang("MAINT_PLEASE");?></p>
<?php
}?>

		</div>
                           <?php  languageSwitcher();?>
	</div>
</div>




</div>
</div>
<!-- Place any per-page javascript here -->


<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
