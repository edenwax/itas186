<?php 
/*
 * register.php
 * User Registration
 *
 * Users are required to provide their first name, last name, phone number, address, email address, username and password.
 * Username must be unique, passwords must be at least 8 characters long
 *
*/

require_once("User.php");
require_once("header.php");

//logged in users shouldnt be able to see this page, redirecting
if(isset($_SESSION['username']) && $_SESSION['logged_in'] == TRUE) {
	header('Location:index.php');
}

//check if all fields contain values
if(isset($_POST['fname']) && ($_POST['lname']) && ($_POST['address']) && ($_POST['phone']) && ($_POST['username']) && ($_POST['email']) && ($_POST['password']) && ($_POST['confirm'])) {
	$errors = FALSE;
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
	if(strlen($password) < 8) {
		echo 'Password is too short. It needs to be 8 or more characters long.';
		$errors = TRUE;
	}
	if($password != $confirm) {
		echo 'Passwords don\'t match, try again.';
		$errors = TRUE;
	}

	$users = User::findAll();

	foreach($users as $x) {
		if($x->getUsername() == $username) {
			echo 'Username already exists.';
			$errors = TRUE;
			break;
		}
	}

	$md5password = md5($password);
	//if password passes checks, execute registration
	if($errors == FALSE) {
		$user = new User();
		$user->setFirstName($first_name);
		$user->setLastName($last_name);
		$user->setAddress($address);
		$user->setPhoneNum($phone_num);
		$user->setEmail($email);
		$user->setUsername($username);
		$user->setPassword($md5password);
		$user->setUserType($user_type);
		$user->save();


		//$_SESSION['username'] = $username;
		//$_SESSION['user_id'] = $user->getId();
		//$_SESSION['logged_in'] = TRUE;

		header('Location:myboats.php');
	}

	//echo 'Created new user: <br/><strong>' . $firstname . ' ' . $lastname . '</strong><br/><br/>';
} 
?>

<form action="" method="POST" enctype="multipart/form-data">
<div class="form-cont">
	<div class="form-wrap">
		<div class="labels">
			<ul>
				<li><h4>Register</h4></li>
				<li><label>First Name:</label></li>
				<li><label>Last Name:</label></li>
				<li><label>Address:</label></li>
				<li><label>Phone Number:</label></li>
				<li><input type="submit" name="submit" value="Sign up" /></li>
			</ul>
		</div>

		<div class="inputs">
			<ul>
				<li></li>
				<li><input type="text" name="fname" placeholder="Bob" required /></li>
				<li><input type="text" name="lname" placeholder="Roberts" required /></li>
				<li><input type="text" name="address" placeholder="123 Home St" required /></li>
				<li><input type="text" name="phone" placeholder="555 5555" required /></li>
			</ul>
		</div>
	</div>

	<div class="form-wrap">
		<div class="labels">
			<ul>
				<li><h4>Credentials</h4></li>
				<li><label>Email:</label></li>
				<li><label>Username:</label></li>
				<li><label>Password:</label></li>
				<li><label>Confirm:</label></li>
				<li class="checkbox"><div><input type="checkbox" name="user_type" id="user_type" value="1" /></div><div><label for="user_type">Vessel Owner:</label></div></li>
			</ul>
		</div>

		<div class="inputs">
			<ul>
				<li></li>
				<li><input type="email" name="email" required /></li>
				<li><input type="text" name="username" required /></li>
				<li><input type="password" name="password" required /></li>
				<li><input type="password" name="confirm" required /></li>
			</ul>
		</div>
	</div>
</div>
<p>All fields are required to register at the VIU ITAS Marina.</p>
</form>