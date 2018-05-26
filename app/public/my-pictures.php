<?php

$pageTitle = "my-pictures";

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
			<h1>My Pictures</h1>
		</div>
	</div>

	<div id="feed" class="row no-gutters">
	</div>

	<canvas id="canvas"></canvas>
</div>
<?= $afterMainContent ?>