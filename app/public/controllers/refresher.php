<?php

session_start();
include_once '../config/config.php';
include_once "../views/header.php";

if ($_POST && isset($_POST['toRefresh']))
{
	if ($_POST['toRefresh'] == 'header')
		echo $headerContent;
}