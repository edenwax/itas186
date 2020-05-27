<?php
// login.php

// we will need the User class
require_once("User.php");
require_once("header.php");

if(isset($_SESSION['username'])) {
	header('Location:index.php');
}

// check if they have posted the form with the new username/passwod
if(isset($_POST['username']) && ($_POST['password'])) {
	// Sanitize the username and password from POST:
	$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
	$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

	// Call the User class to check if this a valid user in the database
	$user = User::checkUser($username, $password);

	if ($user != null) {
	// Create a new user
		$_SESSION['username'] = $user->getUsername();
		$_SESSION['user_id'] = $user->getId();
		$_SESSION['logged_in'] = TRUE;

		header('Location:index.php');
	} else {
		echo "<h2>Sorry, the username or password was incorrect.</h2>";
	}

// else show them the login form
} else {
?>

<form action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="submitted" id="submitted" value="1" />
	<div class="form-wrap">
		<div class="labels">
			<ul>
				<li><h4>Login</h4></li>
				<li><label>Username</label></li>
				<li><label>Password</label></li>
				<li><input type="submit" name="submit" value="Login" /></li>
			</ul>
		</div>

		<div class="inputs">
			<ul>
				<li></li>
				<li><input type="text" name="username" maxlenth="50" required /></li>
				<li><input type="password" name="password" maxlength="50" required /></li>
			</ul>
		</div>
	</div>
</form>

<?php
include "footer.php";
// close the else statement
}

?>