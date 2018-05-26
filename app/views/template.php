<?php

session_start();

include_once "meta.php";
include_once "header.php";
include_once "footer.php";

$beforeMainContent = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
	{$metaContent}
</head>
<body>
	{$headerContent}
HTML;

$afterMainContent = <<<HTML
	{$footerContent}
	
	<script src="%s/config.js"></script>
	<script src="%s/utils.js"></script>
	<script src="%s/ajax.js"></script>
	<script src="%s/modals.js"></script>
	<script src="%s/header.js"></script>
	<script src="%s/{$pageTitle}.js"></script>
</body>
</html>
HTML;

$afterMainContent = sprintf($afterMainContent, JS_D, JS_D, JS_D, JS_D, JS_D, JS_D);