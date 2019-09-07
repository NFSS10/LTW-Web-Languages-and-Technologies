<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Login";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	require('lib/password.php');
	
	// Check if there is already a session
	if(!isset($_SESSION['login_user']))
	{
		if($_SERVER["REQUEST_METHOD"] == "POST") 
		{
			$username = trim($_POST['username']);
			$submitPressed = $_POST['submit'];
			$password = trim($_POST['password']);
			
			include_once('database/connection.php');
			include_once('database/queries.php');

			if(isset($submitPressed))
			{
				try 
				{
					$count = getCurrentUserInfo($db, $username);
				} 
				catch (PDOException $e) 
				{
					die($e->getMessage());
				}

				if (!($count && password_verify($password, $count['password'])))
				{
					?>
					<script type="text/javascript" src="scripts/login_display.js"> </script>
				<?php

				}
				else
				{
					$_SESSION['login_user'] = $username;
					header("Location:index.php");
				}
			}
		}
	}
	else
	{
		header("Location:index.php");
	}
?>
	<script type="text/javascript" src="scripts/login_check.js"> </script>

		<div class="login_register">
			<form method="POST" action="" id="login_form">
				<div class="login_container">
                    <div class="header_group">
                        <label class="l_r_header">Sign In</label>
                    </div>
					<input type="text" name="username" id="username" placeholder="Username"><br>
					<input type="password" name="password" id="password" placeholder="Password"><br><br>
					<span class="login_span"></span>
					<input type="submit" value="Sign In" id="submit" name="submit"><br>
					<p> You don't have an account yet? <a href="registration.php" class="to_registration"> Register here </a></p>
				</div>
			</form>
	</div>

<?php
	include ('templates/footer.php'); 
?>


