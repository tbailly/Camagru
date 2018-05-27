<?php

$pageTitle = 'feed';

include_once '../config/config.php';
include_once VIEWS_D . '/template.php';

?>

<!--
	HTML CONTENT
-->
<?= $beforeMainContent ?>
<div id="main-container" class="container">
	<div class="row">
		<div class="col-12">
			<h1>Feed</h1>
		</div>
	</div>
	<div id="feed" class="row no-gutters">
		<!-- All pictures added with feed.js -->
	</div>
</div>
<div id="picture-details-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            	<!-- Modal content added with feed.js -->
            </div>
        </div>
    </div>
</div>
<?= $afterMainContent ?>
