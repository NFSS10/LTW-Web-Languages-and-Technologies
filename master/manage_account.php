<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Manage Account";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	if(isset($_SESSION['login_user']))
	{
		?>
			<div class="manage_all">
		<?php
			include('templates/left_menu.php');
		?>
			</div>
		<?php
	}
	else
	{
		include ('templates/no_login.php');
	}

	include ('templates/footer.php');
?>
