<?php 
//add boat

require_once("Boat.php");
require_once("User.php");
require_once("header.php");

error_reporting(0); // ONLY used after work complete to suppress errors

if(isset($_POST['name']) && ($_POST['reg']) && ($_POST['length']) && ($_SESSION['user_id']) && ($_FILES['image'])) {
    $errors		= false;
    $file_name 	= $_FILES['image']['name'];
    $file_size 	= $_FILES['image']['size'];
    $file_tmp 	= $_FILES['image']['tmp_name'];
    $file_type 	= $_FILES['image']['type'];
    $file_ext 	= strtolower(end(explode('.',$file_name)));

    $extensions = array("jpeg","jpg","png","gif");

    if(in_array($file_ext,$extensions) === false){
        echo 'Incorrect file extension. Only JPG, PNG or GIF accepted.';
        $errors = true;
    }
    if($file_size > 5120000){
        echo 'File size must be 5MB or smaller.';
        $errors = true;
    }
    if($file_size == 0) {
    	echo 'An image is required to add a boat.';
    	$errors = true;
    }

    $boats = Boat::findAll();

    foreach($boats as $alreg) {
    	if($alreg->getRegNum() == $_POST['reg']) {
    		echo '<h3 class="error">This registration number is already assigned to another boat in the database.</h3>';
    		$errors = true;
    		break;
    	}
    }

    if($errors == false) {
        move_uploaded_file($file_tmp,"images/".$file_name);
	    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	    $reg_num = filter_var($_POST['reg'], FILTER_SANITIZE_NUMBER_INT);
	    $length = filter_var($_POST['length'], FILTER_SANITIZE_NUMBER_INT);
	    $user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);

	    $boat = new Boat();

	    $boat->setName($name);
	    $boat->setRegNum($reg_num);
	    $boat->setLength($length);
	    $boat->setUserId($user_id);
	    $boat->setImage($file_name);
	    $boat->save();
	    header('Location:myboats.php');
	}
}
?>

<h1>Add a new boat to the VIU Marina</h1>
<form action="" method="POST" enctype="multipart/form-data">
	<div class="form-wrap">
		<div>
			<ul>
				<li><label>Boat Name:</label></li>
				<li><label>Registration #:</label></li>
				<li><label>Length (ft):</label></li>
				<li><label>Image:</label></li>
				<li><input type="submit" name="submit" value="Add Boat" /></li>
			</ul>
		</div>

		<div>
			<ul>
				<li><input type="text" name="name" placeholder="RMS Titanic" required /></li>
				<li><input type="text" name="reg" placeholder="401" required /></li>
				<li><input type="text" name="length" placeholder="882.5" required /></li>
				<li><input type="file" name="image"/></li>
			</ul>
		</div>
	</div>
</form>
