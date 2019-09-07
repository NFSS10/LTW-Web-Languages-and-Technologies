<!DOCTYPE html>
<html>
	<head>
		<title>ResReview</title>
		<meta charset="utf-8">
		<link rel="icon" href="resources/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/login_register.css">
		<link rel="stylesheet" type="text/css" href="css/restaurant.css">
		<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
	</head>
	<body>
		<div class="layer_body">
			<header>
				<div class="logo">
					<a href="index.php">
						<img src="resources/logo.png" alt="logo">
					</a>
				</div>
			</header>
			<div class="menu">
				<ul class="Navigation_bar" id="nv">
					<li class="Home"><a class="homepage" href="index.php"><img src="resources/homepage.png" alt="Homepage"></a></li>
				<?php
					if(isset($_SESSION['login_user']))
					{
						?>
						<li><a href="manage_account.php">Manage Account</a></li>
						<li><a href="logout.php">Logout</a></li>

                        <form method="POST" action="list_restaurants.php">
						<div class='search_container'>
                            <div class="d_selected">
                                <select name="sel_order">
                                    <optgroup label="Order by">
                                        <option value="name">Name</option>
                                        <option value="price">Price</option>
                                    </optgroup>
                                </select>
                            </div>
							<input type='search' id='search' name="search" placeholder='Search...' />
							<input class='search_icon' name="search_form" type="submit" />
						</div>
					</form>
                    <?php
					}
					else
					{
						?>
						<li><a href="login.php">Login</a></li>
						<li><a href="registration.php">Register</a></li>
					<?php
					}
				 	?>

					<li class="icon">
						<a href="javascript:void(0);" style="font-size:15px; color: #000000;" onclick="checkResponsive()">☰</a> </li>
				</ul>
			</div>

			<script>
				function checkResponsive() {
					var x = document.getElementById("nv");
					if (x.className === "Navigation_bar") {
						x.className += " responsive";
					} else {
						x.className = "Navigation_bar";
					}
				}
			</script>
			<div class="main">
