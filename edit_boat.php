<?php
//edit_boat.php

require_once "Boat.php";
require_once "User.php";
require_once "header.php";

if(isset($_GET['id'])) {
	$boat = Boat::find($_GET['id']);
	$oid = $boat->getUserId();
	$user = User::find($oid);

	if($boat->getUserId() == $_SESSION['user_id']) {
	echo '<form action="" method="POST" enctype="multipart/form-data">
	<img src="images/' . $boat->getImage() . '" alt=" " />
	<div class="form-wrap">
		<div>
			<ul>
				<li><label>Boat Name:</label></li>
				<li><label>Registration #:</label></li>
				<li><label>Length (ft):</label></li>
				<li><label>Image:</label></li>
				<li><input type="submit" name="submit" value="Edit Boat" /></li>
			</ul>
		</div>

		<div>
			<ul>
				<li><input type="text" name="name" value="' . $boat->getName() . '" /></li>
				<li><input type="text" name="reg" value="' . $boat->getRegNum() . '" /></li>
				<li><input type="text" name="length" value="' . $boat->getLength() . '" /></li>
				<li><input type="hidden" name="image2" value="' . $boat->getImage() . '" /><input type="file" name="image" /></li>
			</ul>
		</div>
	</div>
</form>';
} else {
	//make questionable user believe boat doesnt exist
	echo '<h1>Boat does not exist.</h1>';
	//got em
}

	if(isset($_POST['submit']) && $boat->getUserId() == $_SESSION['user_id']) {
		if(isset($_POST['name']) && ($_POST['reg']) && ($_POST['length']) && ($_SESSION['user_id'])) {
			$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	    	$reg_num = filter_var($_POST['reg'], FILTER_SANITIZE_NUMBER_INT);
	    	$length = filter_var($_POST['length'], FILTER_SANITIZE_NUMBER_INT);
	    	$user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);

	    	$default = filter_var($_POST['image2'], FILTER_SANITIZE_STRING);

	    	$errors = FALSE;

	    	$filename = '';

			if(isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
				$filename	= $_FILES['image']['name'];
				$filesize   = $_FILES['image']['size'];
				$filetmp	= $_FILES['image']['tmp_name'];
				$filetype	= $_FILES['image']['type'];
				$fileext 	= strtolower(end(explode('.',$filename)));
				$extensions = array("jpeg", "jpg", "png", "gif");
				//gotta have those gifs for the memes

				if(in_array($fileext, $extensions) === false) {
					echo 'Extension not supported. Must be a JPG, PNG or GIF.';
					$errors = TRUE;
				}
				if($filesize > 5120000) {
					echo 'File size must be 5MB or below.';
					$errors = TRUE;
				}
				if($errors === FALSE) {
					move_uploaded_file($filetmp,'images/'.$filename);
					
					echo '<strong>Successfully added image</strong><br />';
				}
			}

			$boat->setName($name);
			$boat->setRegNum($reg_num);
			$boat->setLength($length);
			//$boat->setImage($filename);
			if(empty($filename)) {
				$boat->setImage($default);
				//echo '<br/>No image detected';
			} else {
				$boat->setImage($filename);
			}
			$boat->setUserId($user_id);

			//var_dump($boat);

			if($boat->update()) {
				echo '<br/>Boat has been updated</br>';
			}
		} else {
			echo '<strong>Missing value detected.</strong> Ensure all fields are filled out.';
		}
	}
}

?>
