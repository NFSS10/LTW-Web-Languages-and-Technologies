<?php
	session_start();
	session_regenerate_id(true);

	if(isset($_SESSION['login_user']))
	{

		if(isset($_POST["selected_restaurant"]))
		{
			$id = $_POST["selected_restaurant"];

			if($id < 0) {
				header("Location:editRestaurant.php");
			}
			else
			{
				include_once('database/connection.php');
				include_once('database/queries.php');

				try
				{
					deleteRestaurant($db, $id);
				}
				catch (PDOException $e)
				{
					die($e->getMessage());
				}
				deleteRestIMGs($db, $id);

			}
		}
	}
	else
	{
		include ('templates/no_login.php');
	}

	header("Location: editRestaurant.php");
?>
