<?php
//myboats.php

require_once("User.php");
require_once("Boat.php");
require_once("Database.php");
require_once("header.php");

if(isset($_SESSION['username']) && ($_SESSION['logged_in'])) {
	$id = $_SESSION['user_id'];

	$user = User::find($id);
	$old_user = $user->getUsername();

	$user_type = 0;

	echo '<form action="" method="POST" enctype="multipart/form-data">
<div class="form-cont">
	<div class="form-wrap">
		<div class="labels">
			<ul>
				<li><h4>Update Profile</h4></li>
				<li><label>First Name:</label></li>
				<li><label>Last Name:</label></li>
				<li><label>Address:</label></li>
				<li><label>Phone Number:</label></li>
				<li><input type="submit" name="submit" value="Update Profile" /></li>
			</ul>
		</div>

		<div class="inputs">
			<ul>
				<li></li>
				<li><input type="text" name="fname" value="' . $user->getFirstName() . '" required /></li>
				<li><input type="text" name="lname" value="' . $user->getLastName() . '" required /></li>
				<li><input type="text" name="address" value="' . $user->getAddress() . '" required /></li>
				<li><input type="text" name="phone" value="' . $user->getPhoneNum() . '" required /></li>
			</ul>
		</div>
	</div>

	<div class="form-wrap">
		<div class="labels">
			<ul>
				<li></li>
				<li><label>Email:</label></li>
				<li><label>Username:</label></li>
				<li><label>Password:</label></li>
				<li><label>Confirm:</label></li>
			</ul>
		</div>

		<div class="inputs">
			<ul>
				<li></li>
				<li><input type="email" name="email" value="' . $user->getEmail() . '" required /></li>
				<li><input type="text" name="username" value="' . $user->getUsername() . '" required /></li>
				<li><input type="password" name="password" required /></li>
				<li><input type="password" name="confirm" required /></li>
				<input id="user_type_hidden" type="hidden" value="0" name="user_type" />
				<li class="checkbox"><div><input type="checkbox" name="user_type" id="user_type" value="1" ';
				if($user->getUserType() == 1) {
					echo 'checked';
				}
echo ' /></div><div><label for="user_type">Vessel Owner</label></div></li>
			</ul>
		</div>
	</div>
</div>
<p>Please re-enter your password to confirm changes or set a new password.</p>
</form>';

//echo '<div class="del-button"><a href="delete_user.php?id=' . $_SESSION['user_id'] . '"><i class="fas fa-user-times"></i> &nbsp; Delete account</a></div>';

echo '<form onSubmit="if(!confirm(\'Are you sure you want to delete your ITAS Marina account?\')){return false;}" method="post" action="user_delete.php?id=' . $_SESSION['user_id'] . '">
                            <button type="submit" name="del_account" class="del-button"><i class="fas fa-user-times"></i> &nbsp; Delete account</button>
                        </form>
';

	if(isset($_POST['fname']) && ($_POST['lname']) && ($_POST['address']) && ($_POST['phone']) && ($_POST['username']) && ($_POST['email']) && ($_POST['password']) && ($_POST['confirm'])) {
		$errors = false;
		$first_name = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
		$last_name 	= filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
		$address 	= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$phone_num 	= filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
		$username 	= filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		$email 		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$user_type  = $_POST['user_type'];
		$password 	= $_POST['password'];
		$confirm   	= $_POST['confirm'];

		//check password length and if password and confirm match
		//disabled for marking/testing purposes
		/*if(strlen($password) < 8) {
			echo 'Password is too short. It needs to be 8 or more characters long.';
			$errors = true;
		}*/
		if($password != $confirm) {
			echo 'Passwords don\'t match, try again.';
			$errors = true;
		}
		if($username != $old_user) {
			$users = User::findAll();

			foreach($users as $x) {
				if($x->getUsername() == $username) {
					echo 'Username already exists.';
					$errors = true;
					break;
				}
			}
		}

		$md5password = md5($password);
		
		//if passes checks, execute profile update
		if($errors == false) {
			$user->setFirstName($first_name);
			$user->setLastName($last_name);
			$user->setAddress($address);
			$user->setPhoneNum($phone_num);
			$user->setEmail($email);
			$user->setUsername($username);
			$user->setPassword($md5password);
			$user->setUserType($user_type);
			echo '<h3 class="error">';
			$user->update();
			echo '</h3>';

			$_SESSION['username'] = $username;

		}
	}

} else {
	header('Location:index.php');
}

include "footer.php";

?>

