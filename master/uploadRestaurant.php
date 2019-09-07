<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Upload Restaurant";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	if(isset($_SESSION['login_user']))
	{
		$owner = $_SESSION['login_user'];
		$restaurantname = strip_tags($_POST["name"]);
		$address = strip_tags($_POST["address"]);
		$city = strip_tags($_POST["city"]);
		$price = strip_tags($_POST["price"]);
		$description = strip_tags($_POST["description"]);
		$longitude = strip_tags($_POST["longitude"]);
		$latitude = strip_tags($_POST["latitude"]);
		$phone = strip_tags($_POST["phone"]);

		$img1 = $_FILES['image1']['tmp_name'];
		$img2 = $_FILES['image2']['tmp_name'];
		$img3 = $_FILES['image3']['tmp_name'];

		$img1type = $_FILES['image1']['type'];
		$img2type = $_FILES['image2']['type'];
		$img3type = $_FILES['image3']['type'];


		include_once('database/connection.php');
		include_once('database/queries.php');

		try
		{
			$id = addRestaurant($db, $restaurantname, $address, $city, $price, $description, $longitude, $latitude, $phone, $owner);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		include_once('uploadRestaurantImg.php');
		if(!empty($img1))
			uploadRestImage($db, $id, 1, $img1, $img1type);
		if(!empty($img2))
			uploadRestImage($db, $id, 2, $img2, $img2type);
		if(!empty($img3))
			uploadRestImage($db, $id, 3, $img3, $img3type);

	}
	else
	{
		include ('templates/no_login.php');
	}

	header('Location: restaurant.php?id='.$id.'');

?>
