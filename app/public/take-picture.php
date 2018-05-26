<?php

$pageTitle = "take-picture";

include_once '../config/config.php';
include_once VIEWS_D . '/template.php';
include_once MODELS_D . '/route.php';

?>

<?= $beforeMainContent ?>
	<div id="main-container" class="container">

		<div class="row">
			<div class="col-12">
				<h1>Take a picture and share it with your friends !</h1>
			</div>
		</div>

		<div class="row py-3">
			<div class="col-4">
				<select name="frame-filters" id="frame-filters">
					<option value="">Choose a frame filter</option>
				</select>
			</div>
			<div class="col-4">
				<select name="object-filters" id="object-filters">
					<option value="">Choose an object filter</option>
				</select>
			</div>
			<div class="col-4">
				<select name="classic-filters" id="classic-filters">
					<option value="">Choose another filter</option>
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-sm-12 height-100">
				<div id="preview-wrapper" class="my-2">
					<video id="video"></video>
					<img id="uploaded-picture">
					<div id="filters-wrapper"></div>
				</div>
				<button type="button" id="startbutton" class="btn btn-primary"><img src="./img/camera.svg" alt="take photo"></button>
				<input id="upload-picture" type="file">
				<button type="button" id="helpbutton" class="btn btn-primary">Help</button>
				<canvas id="canvas"></canvas>
			</div>
			<div class="col-md-6 col-sm-12 height-100">
				<div id="pictures-taken-wrapper">
					<div class="picture-taken">
					</div>
				</div>
			</div>
		</div>
		
	</div>

	<div id="share-picture-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Share a picture</h5>
				<button type="button" class="close close-share-picture-modal">
					<span>&times;</span>
				</button>
			</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<div id="help-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Help</h5>
					<button type="button" class="close close-help-modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<ul>
						<li>First, pick a filter in one of the lists</li>
						<li>Then, you can apply changes with a drag and drop</li>
						<li>If you double click on the filter, you'll change the mode.</li>
						<li>The three modes are respectively:
							<ul>
								<li>Move</li>
								<li>Resize</li>
								<li>Rotate</li>
							</ul>
						</li>
						<li>To delete a filter, put your mouse on it and press "D"</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?= $afterMainContent ?>