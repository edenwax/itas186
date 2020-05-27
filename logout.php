<?php
// logout.php

if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$_SESSION['logged_in'] 	= FALSE;
	$_SESSION['username'] 	= NULL;

	session_unset();
	session_destroy();

	header('Location:index.php');
}