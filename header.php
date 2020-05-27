<?php
	session_start();
	//error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
    <title>ITAS Marina Web App</title>
    <link rel="stylesheet" href="css/main.css" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body>
	<header>
		<nav>
			<div class="nav-wrap">
			<?php
				//only users who are logged in can view their boats, edit their profile and log out
				if(isset($_SESSION['username']) && $_SESSION['logged_in'] == TRUE) {
					$user = User::find($_SESSION['user_id']);
					$firstname = $user->getFirstName();
					echo '<div class="main-nav"><a href="index.php">Home</a>
						<a href="myboats.php">My Boats</a>
						</div>

						<div class="user-nav">
						<a href="profile.php">' . $firstname . '\'s Profile</a>
						<a href="logout.php">Logout</a></div>';
				} else {
					// user isnt logged in, show default nav
					echo '<div class="main-nav"><a href="index.php">Home</a></div>
						<div class="user-nav">
							<a href="login.php">Login</a>
							<a href="register.php">Register</a></div>';
				}
			?>
			</div>
		</nav>
	</header>
	<div class="header">
		<section class="head">
			<div class="logo">
				<h1><i class="fas fa-anchor"></i> ITAS <span>Marina</span></h1>
			</div>
			<div class="add-boat-nav">
				<?php
					// check if user is logged in, only users that are logged in can add boats
					if(isset($_SESSION['user_id']) && ($_SESSION['username'])) {
						$user = User::find($_SESSION['user_id']);
						$user_type = $user->getUserType();
						//check if user is an owner (user_type 1), user must be an owner to add a boat
						if($user_type == 1) {
							echo '<a href="add_boat.php">Add Boat</a>';
						}
					}
				?>
			</div>
		</section>
	</div>
	<div class="page-wrap">
		<section class="main">