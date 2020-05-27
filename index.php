<?php
/**
 * index.php
 * Main entry point into the marina application
 *
 * VIU Marina, an ITAS186 Assignment 04 Project by Colin Berry
 *
 * Function: Users can view docked boats but only registered users, who are owners, can add boats. Users can view and update their account information and delete their accounts.
 * Owners can also add, edit and delete their boats. Boats are required to have a name, registration number, length and photo. Registration numbers must be unique.
 * I've also ensured that users can not delete other users' boats and accounts. 
 *
 */

//require boat class
require_once("Boat.php");
//require user class
require_once("User.php");
//require database
require_once("Database.php");
//include the header.php on all pages
require_once("header.php");

//create database object
$db = Database::connect(); 

//find all boats for index list
$boats = Boat::findAll();

echo '<h2>Currently Docked</h2><div class="line"></div>';

echo '<div class="docked-table">';

foreach($boats as $boat) {
    $user = User::find($boat->getUserId());
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

    // The list view for boats only needs to show the first photo (or none)
    
echo '</div>';

    // Put the id of the boat in the URL using GET protocol
}
echo '</div>';

include "footer.php";




