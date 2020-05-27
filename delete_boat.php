<?php
//delete_boat.php

require_once("Boat.php");
require_once("User.php");
require_once("header.php");
//require_once ("footer.php");

if (isset($_GET['id'])) {
	$user_id = $_SESSION['user_id'];

    $boat = Boat::find($_GET['id']);

    if($boat->getUserId() == $user_id) {
    	$boat->delete();
    } else {
    	echo 'You do not own this boat! Try logging in and try again.';
    }
    header('Location:myboats.php');
}