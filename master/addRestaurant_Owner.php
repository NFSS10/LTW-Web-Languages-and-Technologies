<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Add Owner";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	if(isset($_SESSION['login_user']))
	{
		$restID = $_POST["selected_restaurant"];

		if(!$restID) {
			$restID = $_POST["restaurant_id_reload"];
		}

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$submitPressed = $_POST['submit'];
			$username = strip_tags($_POST['username']);

			include_once('database/connection.php');
			include_once('database/queries.php');

			if(isset($submitPressed))
			{
				try
				{
					$userInfo = getCurrentUserInfo($db, $username);
				}
				catch (PDOException $e)
				{
					die($e->getMessage());
				}

				if($userInfo) //user existe
				{
					$usernameAdd = $userInfo["username"];
					try
					{
						addOwnerRestaurant($db, $usernameAdd, $restID);
					}
					catch (PDOException $e)
					{
						die($e->getMessage());
					}
					?>
					<script type="text/javascript" src="scripts/add_owner_success_msg.js"> </script>
					<?php
				}
				else
				{//user nao existe
					?>
					<script type="text/javascript" src="scripts/add_owner_error_msg.js"> </script>
				<?php
				}
			}
		}
?>
	<div class="add_restaurant_owner">
		<?php
			include('templates/left_menu.php');
		?>
		<div class="add_r_o_form">
			<span id="add_r_success"></span>
			<span id="add_r_nau"></span>
			<form action="" method="POST" enctype="multipart/form-data" class="add_r_form">
				<label>Username:</label><br>
				<input type="text" name="username" placeholder="valid username here..." required>
				<input type="hidden" name="restaurant_id_reload" value="<?php echo $restID; ?>"/>
				<input type="submit" name="submit" value="Add New Owner"/>

			</form>
		</div>
	</div>

<?php
	}
	else
	{
		include ('templates/no_login.php');
	}

	include ('templates/footer.php');
?>
