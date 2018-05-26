<?php

$pageTitle = 'confirm-account';

include_once '../config/config.php';
include_once VIEWS_D . '/template.php';
include_once MODELS_D . '/confirm-account-model.php';

$message = init();
$mainContent = setMainContent($message);

function setMainContent($message) {
	if (strpos($message, 'Error: ') === FALSE)
		return ("<p class='success-message'>$message</p>");
	else
		return ("<p class='error-message'>$message</p>");
}

?>

<!--
	HTML CONTENT
-->
<?= $beforeMainContent ?>
<div id="main-container" class="container">
	<h1>Confirm account</h1>
	<div>
		<?= $mainContent ?>
	</div>
</div>
<?= $afterMainContent ?>
