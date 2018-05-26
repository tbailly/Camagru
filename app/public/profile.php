<?php

$pageTitle = "profile";

include_once '../config/config.php';
include_once VIEWS_D . '/template.php';
include_once MODELS_D . '/route.php';

?>

<!--
	HTML CONTENT
-->
<?= $beforeMainContent ?>
<div id="main-container" class="container">
	<div class="row">
		<div class="col-12">
			<h1>Profile</h1>
		</div>
	</div>

	<div id="profile" class="row justify-content-center">
		<div class="col-lg-3 col-md-4 col-sm-6 col-8">
			<?php if ($_SESSION['logged_on_user']['profile_img'] === '0') { ?>
			<img id="profile-picture" class="img-fluid" src="./img/user-placeholder.png" alt="profile-picture">
			<?php } else { ?>
			<img id="profile-picture" class="img-fluid" src="../pictures/profiles/<?= $_SESSION['logged_on_user']['id_user'] ?>.jpg" alt="profile-picture">
			<?php } ?>
		</div>
	</div>
	
	<div class="row justify-content-center">
		<div class="col-lg-6 col-md-6 col-12">
			<form>
				<div class="form-group">
					<input type="email" id="mail" class="form-control" placeholder="Mail" value="<?= $_SESSION['logged_on_user']['mail']?>">
				</div>
				<div class="form-group">
					<input type="text" id="username" class="form-control" placeholder="Username" value="<?= $_SESSION['logged_on_user']['username']?>">
				</div>
				<div class="form-group">
					<input type="text" id="firstname" class="form-control" placeholder="Firstname" value="<?= $_SESSION['logged_on_user']['firstname']?>">
				</div>
				<div class="form-group">
					<input type="text" id="lastname" class="form-control" placeholder="Lastname" value="<?= $_SESSION['logged_on_user']['lastname']?>">
				</div>
				<div class="form-group">
					<input type="password" id="password" class="form-control" placeholder="Password">
					<input type="password" id="password-confirm" class="form-control" placeholder="Confirm password">
				</div>
				<div class="form-group">
					<label for="mail-preference">Send mail notification on comment</label>
					<?php if ($_SESSION['logged_on_user']['mail_preference']) { ?>
					<input type="checkbox" id="mail-preference" class="form-control" checked>
					<?php } else { ?>
					<input type="checkbox" id="mail-preference" class="form-control">
					<?php } ?>
				</div>
				<div class="form-group">
					<input type="submit" id="update-user" class="form-control" value="Update Details">
				</div>				
			</form>
		</div>
	</div>
</div>
<?= $afterMainContent ?>