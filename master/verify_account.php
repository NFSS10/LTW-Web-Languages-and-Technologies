<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Account";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	if(isset($_GET['token']) && isset($_GET['username'])) {

		$username = strip_tags($_GET['username']);

		if ($_SESSION['reg_token'] == $_GET['token']) {
			?>

			<div id="account">
				<p>Congratulations <span id="red"><?= $username ?> </span> !</p>
				<p>An activation code has been sent to your email to verify the account! </p>
				<p>If you don't find the verification email, search it on spam. </p>
			</div>

			<?php
		} else {
			?>

			<div id="account_not_access">
				<p>You don't have access to this page!</p>
			</div>

			<?php
		}
	}
	else
	{
		include ('templates/no_login.php');
	}

	include ('templates/footer.php');
?>
