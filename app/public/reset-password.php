<?php

$pageTitle = 'reset-password';

include_once '../config/config.php';
include_once VIEWS_D . '/template.php';

?>

<!--
	HTML CONTENT
-->
<?= $beforeMainContent ?>
	<div id="main-container" class="container">
		
		<div id="password-send-row" class="row justify-content-center">
			<div class="col-lg-4 col-md-6 col-sm-9 col-12 text-center">
				<h1>Reset password</h1>
				<p>Please write down your email.<br>We will send you an email to reset your password</p>
				<form>
		            <div class="form-group">
						<input type="email" id="reset-password-mail" placeholder="E-mail">
		            </div>
		            <button type="submit" id="reset-password-send" class="btn btn-outline-primary">Reset my password</button>
		        </form>
			</div>
		</div>


		<div id="password-confirm-row" class="row justify-content-center">
			<div class="col-lg-4 col-md-6 col-sm-9 col-12 text-center">
				<h1>Reset password</h1>
				<form>
		            <div class="form-group">
						<input type="password" id="password" placeholder="New password">
						<input type="password" id="password-confirm" placeholder="Confirm new password">
		            </div>
		            <button type="submit" id="reset-password-confirm" class="btn btn-outline-primary">Confirm my new password</button>
		        </form>
			</div>
		</div>

	</div>
<?= $afterMainContent ?>