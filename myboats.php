<?php
//myboats.php

require_once("User.php");
require_once("Boat.php");
require_once("Database.php");
require_once("header.php");

if(isset($_SESSION['username']) && ($_SESSION['logged_in'])) {
	$id = $_SESSION['user_id'];
	$username = $_SESSION['username'];

	$boats = Boat::findAll();

	echo '<h2>My Boats</h2><div class="line"></div>';

	foreach($boats as $boat) {
		if($boat->getUserId() == $id) {
			echo '<div class="docked-row">';
		    $photo = $boat->getImage();
		    echo '<div class="docked-img">';
		    if ($photo != null) {
		        echo '<a href="images/' . $boat->getImage() . '"><img src="images/' . $boat->getImage() . '"></a>';
		    } else { // use the default boat image
		        echo "<img src=\"./images/defaultBoat.jpg" . '" height="40" width="40">';
		    }
		    echo '</div>';
		    echo '<div class="docked-title"><div class="docked-name">' . $boat->getName() . '</div>';
    		echo '<div class="docked-reg">REGISTRATION #: <span>' . $boat->getRegNum() . '</span></div></div>';
    		echo '<div class="docked-length">LENGTH<br/><span>' . $boat->getLength() . 'ft</span></div>';
    		echo '<div class="docked-owner">OWNER<br/><span>' . $user->getFirstName() . ' ' . $user->getLastName() . '</span></div>';
    		echo '<div class="docked-control"><a href="edit_boat.php?id=' . $boat->getId() . '"><i class="fas fa-cog"></i></a><a href="delete_boat.php?id=' . $boat->getId() . '"><i class="fas fa-trash-alt"></i></a></div>';

		    // The list view for boats only needs to show the first photo (or none)
		    
			echo '</div>';
		}
	}

	//var_dump($boats);



} else {
	header('Location:index.php');
}