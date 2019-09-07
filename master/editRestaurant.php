<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Edit Restaurant";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	if(isset($_SESSION['login_user']))
	{
		include_once('database/connection.php');
		include_once('database/queries.php');

		try
		{
			$restaurantsArray = getOwnedRestaurants($db, $_SESSION['login_user']);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

?>
	<div class="edit_restaurant">
	<?php
		include('templates/left_menu.php');
	?>

		<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
		<script type="text/javascript"> var restaurants =  <?php  echo json_encode($restaurantsArray);?>; </script>
		<script type="text/javascript" src="scripts/editoptions.js"></script>

		<div class="edit_restaurant_form">
			<form id= "restaurants" method="POST" action="editSelectedRestaurant.php">
				<select name="selected_restaurant"></select>
				<input class="ed_r_edit" type="submit" name="submit" value="Edit"/>
				<button type="submit" class="ed_r_delete" onclick="return confirm('Are you sure you want to delete the restaurant?');" formaction="delete.php">&#10006</button>
				<button type="submit" class="ed_r_addOwner" formaction="addRestaurant_Owner.php">+</button>
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
