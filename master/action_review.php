<?php
	session_start();
	session_regenerate_id(true);

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_SESSION['login_user']))
		{
			$username = $_SESSION['login_user'];
			$rate = $_POST['star'];
			$comment = strip_tags($_POST['comment_review']);
			$restaurant = $_POST['restaurant'];

			include_once('database/connection.php');
			include_once('database/queries.php');

			// Check if a file was selected to upload
			$imageUploaded = !empty($_FILES['image']['name']);
			if ($imageUploaded)
			{
				if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
					echo "Upload failed with error code " . $_FILES['file']['error'];
					echo "<br>Redirecting to previous page in 5 seconds!";
					die(header('refresh:5; url=restaurant.php?id='.$restaurant.''));
				}

				$info = getimagesize($_FILES['image']['tmp_name']);
				if ($info === FALSE) {
					echo "Unable to determine image type of uploaded file!<br>";
					echo "Redirecting to previous page in 5 seconds!";
					die(header('refresh:5; url=restaurant.php?id='.$restaurant.''));
				}

				if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
					echo "The file is not a image!<br>";
					echo "Redirecting to previous page in 5 seconds!";
					die(header('refresh:5; url=restaurant.php?id='.$restaurant.''));
				}

				// Gets number of images in database
				try
				{
					$images = getNumberOfImages($db, $restaurant);
				}
				catch (PDOException $e)
				{
					die($e->getMessage());
				}

				if($images)
				{
					$number = $images['number'];
				}
				else
				{
					$number = 0;
				}

				$number++;

				// Create path if not exist
				if (!file_exists('resources/restaurant/originals')) {
					mkdir('resources/restaurant/originals', 0777, true);
				}
				if (!file_exists('resources/restaurant/thumbs_small')) {
					mkdir('resources/restaurant/thumbs_small', 0777, true);
				}
				if (!file_exists('resources/restaurant/thumbs_medium')) {
					mkdir('resources/restaurant/thumbs_medium', 0777, true);
				}

				$originalFileName = "resources/restaurant/originals/$restaurant-$number.jpg";
				$smallFileName = "resources/restaurant/thumbs_small/$restaurant-$number.jpg";
				$mediumFileName = "resources/restaurant/thumbs_medium/$restaurant-$number.jpg";

				move_uploaded_file($_FILES['image']['tmp_name'], $originalFileName);

				$original = imagecreatefromjpeg($originalFileName);

				$width = imagesx($original);
				$height = imagesy($original);
				$square = min($width, $height);

				// Create small square thumbnail
				$small = imagecreatetruecolor(200, 200);
				imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
				imagejpeg($small, $smallFileName);

				$mediumwidth = $width;
				$mediumheight = $height;

				if ($mediumwidth > 400) {
					$mediumwidth = 400;
					$mediumheight = $mediumheight * ( $mediumwidth / $width );
				}

				$medium = imagecreatetruecolor($mediumwidth, $mediumheight);
				imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
				imagejpeg($medium, $mediumFileName);
			}

			try
			{
				insertReview($db, $username, $restaurant, $rate, $comment);
				if($imageUploaded) {
					insertImageReview($db, $restaurant, $originalFileName, $mediumFileName, $smallFileName);
				}
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