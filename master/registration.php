<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Registration";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	require('lib/password.php');

	if (!isset($_SESSION['reg_token']))
	{
		$_SESSION['reg_token'] = bin2hex(openssl_random_pseudo_bytes(16));
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = strip_tags(trim($_POST['username']));
		$password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
		$email = strip_tags(trim($_POST['email']));
		$submitPressed = $_POST['submit'];
		$code = substr(MD5(mt_rand()), 0, 15);
		$token = $_POST['reg'];

		include_once('database/connection.php');
		include_once('database/queries.php');

		// Verify if already exist a account with the username
		if (isset($submitPressed) && !empty($username) && !empty($password)) {
			try {
				$registeredUser = getUserByUsernameEmail($db, $username, $email);
				$unverifiedUser = getUnverifiedUser($db, $username, $email);
			} catch (PDOException $e) {
				die($e->getMessage());
			}

			if ($unverifiedUser) {
				?>
				<div id="account_already_exist">Already exist an account with that email/username pending for ativation!</div>
			<?php

			} else if ($registeredUser) {
				?>
				<div id="account_already_exist">Already exist an account with that username/email!</div>
				<?php
			} else {
				try {
					insertUnverifiedUser($db, $username, $password, $email, $code);
				} catch (PDOException $e) {
					die($e->getMessage());
				}

				$message = '
Thanks for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.

------------------------
Username: ' . $username . '
Password: ' . $_POST['password'] . '
------------------------

Please click this link to activate your account:
https://paginas.fe.up.pt/~up201207133/trabalho/verify_email.php?email=' . $email . '&code=' . $code . '

';

				$to = $email;
				$subject = "Account Activation For ResReview";
				$from = 'resreview';
				$headers = "From:" . $from;
				mail($to, $subject, $message, $headers);
				header('Location: verify_account.php?username=' . $username . '&token=' . $token . '');
			}
		}
	}
?>
	<script type="text/javascript" src="scripts/registration.js"> </script>

	<div class="login_register">
		<form id="registration" method="POST" action="">
			<div class="header_group">
				<label class="l_r_header">Sign Up</label>
			</div>
			<input type="text" name="username" id="username" required="required" placeholder="Username"><span id="usernamemessage"></span> <br>
			<input type="password" name="password" id="password" required="required" placeholder="Password"><span id="passwordmessage"></span> <br>
			<input type="password" name="password2" id="password2" required="required" placeholder="Confirm Password"><span id="passwordmessage2"></span> <br>
			<input type="e-mail" name="email" id="email" required="required" placeholder="Email"><span id="emailmessage"></span> <br><br>
			<input type="hidden" name="reg" value="<?=$_SESSION['reg_token']?>">
			<input type="submit" value="Register" id="submit" name="submit">
		</form>
	</div>

<?php
	include ('templates/footer.php');
?>
