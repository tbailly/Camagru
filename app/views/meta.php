<?php
$metaContent = <<<HTML
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="%s/bootstrap.min.css">
<link rel="stylesheet" href="%s/main.css">
<link rel="stylesheet" href="%s/{$pageTitle}.css">

<title>{$pageTitle} | Camagru</title>
HTML;

$metaContent = sprintf($metaContent, VENDOR_D, CSS_D, CSS_D);
