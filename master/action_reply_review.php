<?php
	session_start();
	session_regenerate_id(true);

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_SESSION['login_user']))
		{
			include_once('database/connection.php');
			include_once('database/queries.php');

			$username = $_SESSION['login_user'];
			$reply = strip_tags($_POST['review_reply']);
			$restaurant = $_POST['restaurant'];
			$reviewer = $_POST['reviewer'];

			try
			{
				insertReviewReply($db, $reviewer, $restaurant, $reply, $username);
			}
			catch (PDOException $e)
			{
				die($e->getMessage());
			}

			header('Location: restaurant.php?id='.$restaurant.'');
		}
		else
		{
			die(header('Location: restaurant.php?id='.$restaurant.''));
		}
	}
	else
	{
		die(header('Location: index.php'));
	}

?>