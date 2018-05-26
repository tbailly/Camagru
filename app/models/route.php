<?php

if (!isset($_SESSION['logged_on_user']))
{
	header('Location: ' . PUBLIC_D . '/index.php');
}