<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Add Restaurant";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;


	if(isset($_SESSION['login_user']))
	{
?>
			<div class="restaurant_container">

				<?php
				include('templates/left_menu.php');
				?>
				<div class="add_restaurant_form">
                    <form action="uploadRestaurant.php" method="post" enctype="multipart/form-data">

                    <form action="uploadRestaurant.php" method="post" enctype="multipart/form-data">
                    <label>Restaurant name: </label><br>
                    <input type="text" name="name" required><br>

                    <label>Address: </label><br>
                    <input type="text" name="address" required><br>

                    <label>City: </label><br>
                    <input type="text" name="city" required><br>

                    <label>Price: </label><br>
                    <input type="number" name="price" step="0.1" required><br>

                    <label>Description: </label><br>
                    <textarea name="description"  rows="6" cols="70" required></textarea><br>

					<label>Latitude: </label><br>
					<input type="number" step="0.000001" name="latitude" required><br>

                    <label>Longitude: </label><br>
                    <input type="number" step="0.000001" name="longitude" required><br>

                    <label>Phone: </label><br>
                    <input type="number" name="phone" required><br>

                    <label>Images: </label><br>
                    <input type="file" name="image1" accept="image/gif,image/jpeg,image/png" required><br>
                    <input type="file" name="image2" accept="image/gif,image/jpeg,image/png" ><br>
                    <input type="file" name="image3" accept="image/gif,image/jpeg,image/png" ><br><br>

                    <input type="submit" value="Add Restaurant">
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
