<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | User";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	if(isset($_GET['username']))
	{
		$username = strip_tags($_GET['username']);

		include_once('database/connection.php');
		include_once('database/queries.php');

		try
		{
			$user_info = getCurrentUserInfo($db, $username);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		if($user_info) {
			?>
			<div class="user_profile_wrap">
				<div class="user_profile">
					<img id="avatar" src= <?= $user_info['photo'] ?> alt="avatar">
					<div class="user_container">
						<ul>
							<li><span>Name: </span> <?= $user_info['name'] ?></li>
							<li><span>Email: </span> <?= $user_info['email'] ?></li>
							<li><span>City: </span><?= $user_info['city'] ?></li>
							<li><span>Address: </span><?= $user_info['address'] ?></li>
							<li><span>Phone Number: </span><?= $user_info['phone'] ?></li>
						</ul>

			<?php

		if(isset($_SESSION['login_user']) && $_SESSION['login_user'] == $username)
		{
			?>

			<a href="edit_profile.php">Edit your profile</a>

			<?php
		}
		?>
				</div>
			</div>
		</div>
		<?php
		}
		else
		{
			?>

			<div class="user_not_found">
				Username not found in database!
			</div>
			<?php
		}
	}
	else
	{
		?>

		<div class="user_not_found">
			User not found in database!
		</div>
		<?php
	}

	include ('templates/footer.php');
?>

<script type="text/javascript" src="scripts/user.js"> </script>




