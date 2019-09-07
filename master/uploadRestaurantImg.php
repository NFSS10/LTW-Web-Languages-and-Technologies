<?php

	function uploadRestImage($db, $restId, $num, $image, $imageType)
	{
		// Create path if not exist
		if (!file_exists('resources/restaurant/originals'))
			mkdir('resources/restaurant/originals', 0777, true);
		if (!file_exists('resources/restaurant/thumbs_small'))
			mkdir('resources/restaurant/thumbs_small', 0777, true);
		if (!file_exists('resources/restaurant/thumbs_medium'))
			mkdir('resources/restaurant/thumbs_medium', 0777, true);

		if($imageType == "image/jpeg")
		{
			$originalFileName = "resources/restaurant/originals/$restId-$num.jpg";
			$smallFileName = "resources/restaurant/thumbs_small/$restId-$num.jpg";
			$mediumFileName = "resources/restaurant/thumbs_medium/$restId-$num.jpg";
		}
		else if($imageType == "image/png")
		{
			$originalFileName = "resources/restaurant/originals/$restId-$num.png";
			$smallFileName = "resources/restaurant/thumbs_small/$restId-$num.png";
			$mediumFileName = "resources/restaurant/thumbs_medium/$restId-$num.png";
		}
		else if($imageType == "image/gif")
		{
			$originalFileName = "resources/restaurant/originals/$restId-$num.gif";
			$smallFileName = "resources/restaurant/thumbs_small/$restId-$num.gif";
			$mediumFileName = "resources/restaurant/thumbs_medium/$restId-$num.gif";
		}

		include_once('database/connection.php');
		include_once('database/queries.php');

		try
		{
			addPhotoRestaurant($db, $originalFileName, $mediumFileName, $smallFileName, $restId);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		move_uploaded_file($image, $originalFileName);

		if($imageType == "image/jpeg")
			$original = imagecreatefromjpeg($originalFileName);
		else if($imageType == "image/png")
			$original = imagecreatefrompng($originalFileName);
		else if($imageType == "image/gif")
			$original = imagecreatefromgif($originalFileName);

		$width = imagesx($original);
		$height = imagesy($original);
		$square = min($width, $height);

		// Create small square thumbnail
		$small = imagecreatetruecolor(200, 200);
		imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
		if($imageType == "image/jpeg")
			imagejpeg($small, $smallFileName);
		else if($imageType == "image/png")
			imagepng($small, $smallFileName);
		else if($imageType == "image/gif")
			imagegif($small, $smallFileName);

		$mediumwidth = $width;
		$mediumheight = $height;

		if ($mediumwidth > 400) {
		  $mediumwidth = 400;
		  $mediumheight = $mediumheight * ( $mediumwidth / $width );
		}

		$medium = imagecreatetruecolor($mediumwidth, $mediumheight);
		imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
		if($imageType == "image/jpeg")
			imagejpeg($medium, $mediumFileName);
		else if($imageType == "image/png")
			imagepng($medium, $mediumFileName);
		else if($imageType == "image/gif")
			imagegif($medium, $mediumFileName);
}

?>
