<?php

	function getUserByUsernameEmail($db, $username, $email)
	{
		$stmt = $db->prepare('SELECT * FROM user WHERE username = ? or email = ?');
		$stmt->execute(array($username, $email));
		return $stmt->fetch();
	}

	function getUserByUsernamePassword($db, $username, $password)
	{
		$stmt = $db->prepare('SELECT * FROM user WHERE username = ? and password = ?');
		$stmt->execute(array($username, $password));
		return $stmt->fetch();
	}

	function insertUser($db, $username, $password, $email)
	{
		$stmt = $db->prepare('INSERT INTO user (username, password, email) VALUES (?, ?, ?)');
		$stmt->execute(array($username, $password, $email));
	}

    function getCurrentUserInfo($db, $username)
    {
        $stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute(array($username));
        return $stmt->fetch();
    }

	function updateUserInfo($db, $user, $password, $email, $city, $address, $phone, $name)
	{
		$stmt = $db->prepare('UPDATE user SET password = ?, email = ?, city = ?, address = ?, phone = ?, name = ? WHERE username = ?');
		$stmt->execute(array($password, $email, $city, $address, $phone, $name, $user));
	}

	function insertUnverifiedUser($db, $user, $password, $email, $code)
	{
		$stmt = $db->prepare('INSERT INTO unverified_user (username, password, email, code) VALUES (?, ?, ?, ?)');
		$stmt->execute(array($user, $password, $email, $code));
	}

	function getUnverifiedUserEmail($db, $email, $code)
	{
		$stmt = $db->prepare('SELECT * FROM unverified_user WHERE email = ? and code = ?');
		$stmt->execute(array($email, $code));
		return $stmt->fetchAll();
	}

	function getUnverifiedUser($db, $username, $email)
	{
		$stmt = $db->prepare('SELECT * FROM unverified_user WHERE username = ? or email = ?');
		$stmt->execute(array($username, $email));
		return $stmt->fetch();
	}

	function getUnverifiedUserbyUsername($db, $username, $code)
	{
		$stmt = $db->prepare('SELECT * FROM unverified_user WHERE username = ? and code = ?');
		$stmt->execute(array($username, $code));
		return $stmt->fetchAll();
	}

	function deleteUnverifiedUser($db, $email, $code)
	{
		$stmt = $db->prepare('DELETE FROM unverified_user WHERE email = ? and code = ?');
		$stmt->execute(array($email, $code));
	}

	function getRestaurants($db)
    {
        $stmt = $db->prepare('SELECT * FROM restaurant');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getRestaurantPerfilImage($db, $restaurant)
    {
        $stmt = $db->prepare('SELECT mediumPhoto FROM restaurant, photoRestaurant WHERE restaurant.id = photoRestaurant.restaurant AND restaurant.id = ?');
        $stmt->execute(array($restaurant));
        return $stmt->fetch();
    }

    function searchRestaurantsByName($db, $name, $order)
    {
        $stmt = $db->prepare('SELECT * FROM restaurant WHERE name LIKE ? OR address LIKE ? ORDER BY '.$order.' ASC');
        $stmt->execute(array('%'.$name.'%', '%'.$name.'%'));
        return $stmt->fetchAll();
    }

    function getCity($db)
    {
        $stmt = $db->prepare('SELECT DISTINCT city FROM restaurant ORDER BY city');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getMaxPrice($db)
    {
        $stmt = $db->prepare('SELECT MAX(price) AS p FROM restaurant');
        $stmt->execute();
        return $stmt->fetch();
    }

    function getMinPrice($db)
    {
        $stmt = $db->prepare('SELECT MIN(price) AS p FROM restaurant');
        $stmt->execute();
        return $stmt->fetch();
    }

    function getRestaurantsWithSpecsRegRat($db, $city, $rating, $price_min2, $price_max2)
    {
        $rating2 = $rating + 1;
        $stmt = $db->prepare('SELECT restaurant.id FROM restaurant, (SELECT AVG(score) AS sc, restaurant AS ri FROM review, restaurant Group By restaurant) WHERE city = ? AND price >= ? AND price <= ? AND  sc  >= '.$rating.' AND sc < '.$rating2.'  AND restaurant.id = ri');
        $stmt->execute(array($city, $price_min2, $price_max2));
        return $stmt->fetchAll();
    }

    function getRestaurantsWithSpecsReg($db, $city,$price_min2, $price_max2)
    {
        $stmt = $db->prepare('SELECT restaurant.id FROM restaurant, (SELECT AVG(score) AS sc, restaurant AS ri FROM review, restaurant Group By restaurant) WHERE city = ? AND price >= ? AND price <= ? AND restaurant.id = ri');
        $stmt->execute(array($city, $price_min2, $price_max2));
        return $stmt->fetchAll();
    }

    function getRestaurantsWithSpecs($db, $price_min2, $price_max2)
    {
        $stmt = $db->prepare('SELECT restaurant.id FROM restaurant, (SELECT AVG(score) AS sc, restaurant AS ri FROM review, restaurant Group By restaurant) WHERE price >= ? AND price <= ? AND restaurant.id = ri');
        $stmt->execute(array($price_min2, $price_max2));
        return $stmt->fetchAll();
    }

    function getRestaurantsWithSpecsRat($db, $rating, $price_min2, $price_max2)
    {
        $rating2 = $rating + 1;
        $stmt = $db->prepare('SELECT restaurant.id FROM restaurant, (SELECT AVG(score) AS sc, restaurant AS ri FROM review, restaurant Group By restaurant) WHERE price >= ? AND price <= ? AND  sc  >= '.$rating.' AND sc < '.$rating2.'  AND restaurant.id = ri');
        $stmt->execute(array($price_min2, $price_max2));
        return $stmt->fetchAll();
    }

	function getRestaurantById($db, $id)
	{
		$stmt = $db->prepare('SELECT * FROM restaurant WHERE id = ?');
		$stmt->execute(array($id));
		return $stmt->fetch();
	}

	function getRestaurantPhotos($db, $id)
	{
		$stmt = $db->prepare('SELECT * FROM photoRestaurant WHERE restaurant = ?');
		$stmt->execute(array($id));
		return $stmt->fetchAll();
	}

	function getReviews($db, $restaurant)
	{
		$stmt = $db->prepare('SELECT * FROM review WHERE restaurant = ?');
		$stmt->execute(array($restaurant));
		return $stmt->fetchAll();
	}

	function insertReview($db, $user, $restaurant, $score, $text)
	{
		$stmt = $db->prepare('INSERT INTO review (user, restaurant, score, text) VALUES (?, ?, ?, ?)');
		$stmt->execute(array($user, $restaurant, $score, $text));
	}

	function getUserReview($db, $username, $restaurant)
	{
		$stmt = $db->prepare('SELECT * FROM review WHERE user = ? and restaurant = ?');
		$stmt->execute(array($username, $restaurant));
		return $stmt->fetchAll();
	}

	function getRatingOfRestaurant($db, $restaurant)
	{
		$stmt = $db->prepare('SELECT avg(score) AS average FROM review WHERE restaurant = ?');
		$stmt->execute(array($restaurant));
		return $stmt->fetch();
	}

	function isOwner($db, $restaurant, $user)
	{
		$stmt = $db->prepare('SELECT * FROM ownerRestaurant WHERE restaurant = ? and user = ?');
		$stmt->execute(array($restaurant, $user));
		return $stmt->fetchAll();
	}

	function insertReviewReply($db, $reviewer, $restaurant, $reply, $owner)
	{
		$stmt = $db->prepare('UPDATE review SET owner = ?, answer = ? WHERE restaurant = ? and user = ?');
		$stmt->execute(array($owner, $reply, $restaurant, $reviewer));
	}

	function getNumberOfImages($db, $restaurant)
	{
		$stmt = $db->prepare('SELECT count(originalPhoto) AS number FROM photoRestaurant WHERE restaurant = ?');
		$stmt->execute(array($restaurant));
		return $stmt->fetch();
	}

	function insertImageReview($db, $restaurant, $originalFileName, $mediumFileName, $smallFileName)
	{
		$stmt = $db->prepare('INSERT INTO photoRestaurant (originalPhoto, mediumPhoto, smallPhoto, restaurant) VALUES(?, ?, ?, ?)');
		$stmt->execute(array($originalFileName, $mediumFileName, $smallFileName, $restaurant));
	}

	function updateUserPhoto($db, $user, $photo)
    {
        $stmt = $db->prepare('UPDATE user SET photo = ? WHERE username = ?');
        $stmt->execute(array($photo ,$user));
    }

	function deleteOwnerRestaurant($db, $restid)
	{
		$stmt = $db->prepare('DELETE FROM ownerRestaurant WHERE restaurant = ?');
		$stmt->execute(array($restid));
	}

	function addRestaurant($db, $restaurantname, $address, $city, $price, $description, $longitude, $latitude, $phone, $owner)
	{
		//Restaurant info
		$stmt = $db->prepare('INSERT INTO restaurant (id, name, address, city, price, description, longitude, latitude, phone) VALUES (NULL, ?, ?, ?, ?,?, ?, ?, ?)');
		$stmt->execute(array($restaurantname, $address, $city, $price, $description, $longitude, $latitude, $phone));

		$restID = $db->lastInsertId();

		//Owner
		$stmt = $db->prepare('INSERT INTO ownerRestaurant (user, restaurant) VALUES (?, ?)');
		$stmt->execute(array($owner, $restID));

		return $restID;
	}

	function addPhotoRestaurant($db, $originalPhoto, $mediumPhoto, $smallPhoto, $id)
	{
		$stmt = $db->prepare('DELETE FROM photoRestaurant WHERE originalPhoto = ? and mediumPhoto = ? and smallPhoto = ? and restaurant = ?');
		$stmt->execute(array($originalPhoto, $mediumPhoto, $smallPhoto, $id));

		$stmt = $db->prepare('INSERT INTO photoRestaurant (originalPhoto, mediumPhoto, smallPhoto, restaurant) VALUES (?, ?, ?, ?)');
		$stmt->execute(array($originalPhoto, $mediumPhoto, $smallPhoto, $id));
	}

	function getOwnedRestaurants($db, $owner)
	{
		$stmt = $db->prepare('SELECT DISTINCT restaurant.id, restaurant.name FROM restaurant, ownerRestaurant WHERE restaurant.id = ownerRestaurant.restaurant AND ownerRestaurant.user = ?');
		$stmt->execute(array($owner));
		return $stmt->fetchAll();
	}

	function updateRestaurant($db, $id, $restaurantname, $address, $city, $price, $description, $longitude, $latitude, $phone)
	{
		$stmt = $db->prepare('UPDATE restaurant SET name = ?, address = ?, city = ?, price = ?, description = ?, longitude = ?, latitude = ?, phone = ? WHERE id = ?');
		$stmt->execute(array($restaurantname, $address, $city, $price, $description, $longitude, $latitude, $phone, $id));
	}

	function deleteRestIMGs($db, $restId)
	{
		try
		{
			$photos = getRestaurantPhotos($db, $restId);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		for ($i = 0; $i < count($photos); $i++)
		{

			$originalFileName = $photos[$i]['originalPhoto'];
			$smallFileName = $photos[$i]['smallPhoto'];
			$mediumFileName = $photos[$i]['mediumPhoto'];
			unlink($originalFileName);
			unlink($mediumFileName);
			unlink($smallFileName);
		}

		$stmt = $db->prepare('DELETE FROM photoRestaurant WHERE restaurant = ?');
		$stmt->execute(array($restId));
	}

	function deleteRestaurant($db, $id)
	{
		try
		{
			deleteRestIMGs($db, $id);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		$stmt = $db->prepare('DELETE FROM restaurant WHERE id = ?');
		$stmt->execute(array($id));

		try
		{
			deleteOwnerRestaurant($db, $id);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		try
		{
			deleteReviewsRestaurant($db, $id);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}
	}

	function deleteReviewsRestaurant($db, $id)
	{
		$stmt = $db->prepare('DELETE FROM review WHERE restaurant = ?');
		$stmt->execute(array($id));
	}

	function addOwnerRestaurant($db, $owner, $restaurantID)
	{
		$stmt = $db->prepare('SELECT * FROM ownerRestaurant WHERE user = ? and restaurant = ?');
		$stmt->execute(array($owner, $restaurantID));
		$ownerrest = $stmt->fetch();

		if(!$ownerrest)
		{
			$stmt = $db->prepare('INSERT INTO ownerRestaurant (user, restaurant) VALUES (?, ?)');
			$stmt->execute(array($owner, $restaurantID));
		}
	}

	function getTopRestaurants($db)
	{
		$stmt = $db->prepare('SELECT restaurant, avg(score) AS average FROM restaurant INNER JOIN review ON restaurant.id = review.restaurant GROUP BY restaurant ORDER BY average DESC');
		$stmt->execute();
		return $stmt->fetchAll();
	}

?>
