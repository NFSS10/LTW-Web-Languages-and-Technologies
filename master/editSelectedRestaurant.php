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
		if(isset($_POST["selected_restaurant"]))
		{
			$restaurantID = $_POST["selected_restaurant"];

			if($restaurantID < 0) {
				die(header("Location:editRestaurant.php"));
			}
			else
			{
				include_once('database/connection.php');
				include_once('database/queries.php');

				try
				{
					$restaurant = getRestaurantById($db, $restaurantID);
				}
				catch (PDOException $e)
				{
					die($e->getMessage());
				}

			}
		}
?>
	<div class="edit_selected_restaurant">
		<?php
			include('templates/left_menu.php');
		?>

		<div class ="edit_sel_form">
			<form action="update.php" method="POST" enctype="multipart/form-data">
				<label>Images:</label><br>
				<input type="file" name="image1" accept="image/gif,image/jpeg,image/png" ><br>
				<input type="file" name="image2" accept="image/gif,image/jpeg,image/png" ><br>
				<input type="file" name="image3" accept="image/gif,image/jpeg,image/png" ><br><br>

				<label>Restaurant name:</label><br>
				<input type="text" name="name" value="<?php echo $restaurant["name"]; ?>" required><br>

				<label>Address:</label><br>
				<input type="text" name="address" value="<?php echo $restaurant["address"]; ?>" required><br>

				<label>City:</label><br>
				<input type="text" name="city" value="<?php echo $restaurant["city"]; ?>" required><br>

				<label>Price:</label><br>
				<input type="number" name="price" value="<?php echo $restaurant["price"]; ?>" required><br>

				<label>Description:</label><br>
				<textarea name="description" rows="6" cols="70" required><?php echo $restaurant["description"]; ?></textarea><br>

				<label>Latitude:</label><br>
				<input type="number" step="0.000001" name="latitude" value="<?php echo $restaurant["latitude"]; ?>" required><br>

				<label>Longitude:</label><br>
				<input type="number" step="0.000001" name="longitude" value="<?php echo $restaurant["longitude"]; ?>" required><br>

				<label>Phone:</label><br>
				<input type="number" name="phone" value="<?php echo $restaurant["phone"]; ?>" required><br>

				<input type="hidden" name="restaurant_id" value="<?php echo $restaurant["id"]; ?>"/>
				<input type="submit" value="Edit Restaurant" class="edit_rest_submit">
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
