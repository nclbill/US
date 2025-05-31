<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

if(isset($user) && $user->isLoggedIn()){?>


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
	 if(hasPerm([2],$user->data()->id)){



?>
<div class="row">

					<!-- CFORCE 400 Card -->
					<div class="card col-12 col-sm-6 col-md-4">
						<h3 class="card-header"><strong><a href="nouveaux_syndics_admin.php">CFORCE 400</strong></h3>

							<div class="card-body">
								<center><img  src="/espacemoto/users/images/CFORCE.jpg" alt="centered image" width="240" height="150"/></center>
							</div>
							<div class="card-footer">

								<span class="pull-left">Disponible</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></a></span>
							</div>

						</div>
					 <!-- end CFORCE 400 -->



					 <!-- CFORCE 600 Card -->
					 <div class="card col-12 col-sm-6 col-md-4">
						 <h3 class="card-header"><strong><a href="nouveaux_syndics_admin.php">CFORCE 600</strong></h3>

							 <div class="card-body">
								 <center><img  src="/espacemoto/users/images/CFORCE600.jpg" alt="centered image" width="240" height="150"/></center>
							 </div>
							 <div class="card-footer">

								 <span class="pull-left">Disponible</span>
								 <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></a></span>
							 </div>

						 </div>
						<!-- end CFORCE 600 -->

						<!-- 300SR Card -->
 					 <div class="card col-12 col-sm-6 col-md-4">
 						 <h3 class="card-header"><strong><a href="nouveaux_syndics_admin.php">300SR</strong></h3>

 							 <div class="card-body">
 								 <center><img  src="/espacemoto/users/images/300sr.jpeg" alt="centered image" width="240" height="150"/></center>
 							 </div>
 							 <div class="card-footer">

 								 <span class="pull-left">Disponible</span>
 								 <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></a></span>
 							 </div>

 						 </div>
 						<!-- end 300SR -->

	<?php
} // end if permissions
}// end if is loged in
?>

		<div class="jumbotron">
			<h1 align="center"><?=lang("JOIN_SUC");?> <?php echo $settings->site_name;?></h1>
			<p align="center" class="text-muted"><?=lang("MAINT_OPEN")?></p>
			<p align="center">
				<?php
				if($user->isLoggedIn()){?>
					<!--
					<a class="btn btn-primary" href="users/account.php" role="button"><?=lang("ACCT_HOME");?> &raquo;</a>
					-->
				<?php }else{?>
					<a class="btn btn-warning" href="users/login.php" role="button"><?=lang("SIGNIN_TEXT");?> &raquo;</a>
					<a class="btn btn-info" href="users/join.php" role="button"><?=lang("SIGNUP_TEXT");?> &raquo;</a>
				<?php }?>
			</p>
			<br>
			<p align="center"><?=lang("MAINT_PLEASE");?></p>
			<p align="center">
			<img alt="" src="/espacemoto/users/images/CFORCE.jpg" width="240" height="150"/>

		</div>
<?php  languageSwitcher();?>
</div>
</div>
</div>

<!-- Place any per-page javascript here -->
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
