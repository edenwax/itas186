<?php
//delete_user.php

require_once("User.php");
require_once("header.php");
//require_once ("footer.php");

if (isset($_GET['id'])) {
	$user_id = $_SESSION['user_id'];

    $user = User::find($_GET['id']);

    //ensure that the user logged in is the same user thats being deleted
    if($user->getId() == $user_id) {
    	$username = $_SESSION['username'];
		$_SESSION['logged_in'] 	= FALSE;
		$_SESSION['username'] 	= NULL;

		session_unset();
		session_destroy();

    	$user->delete();
    } else {
    	echo 'This is not your account! Try logging in and try again.';
    }
    header('Location:index.php');
}