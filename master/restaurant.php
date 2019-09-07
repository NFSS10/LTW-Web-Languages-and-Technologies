<?php

    session_start();
    session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Restaurant";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;
	?>

<!--	<script type="text/javascript" src="scripts/restaurant.js"> </script>-->
	<script type="text/javascript" src="scripts/jssor.js"> </script>
	<link rel="stylesheet" type="text/css" href="css/jssor.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

<?php

	if(isset($_SESSION['login_user']))
	{
		$username = $_SESSION['login_user'];

		if (!isset($_GET['id'])) {
			?>

			<div class="restaurant_not_found">
				Restaurant not found in database!
			</div>
			<?php
		} else {
			$id = $_GET['id'];

			include_once('database/connection.php');
			include_once('database/queries.php');

			try {
				$restaurant_info = getRestaurantById($db, $id);
			} catch (PDOException $e) {
				die($e->getMessage());
			}

			if (!$restaurant_info) {
				?>

				<div class="restaurant_not_found">
					Restaurant not found in database!
				</div>
				<?php
			} else {
				try {
					$photos = getRestaurantPhotos($db, $id);
					$rating = getRatingOfRestaurant($db, $id);
					$isOwner = isOwner($db, $id, $username);
				} catch (PDOException $e) {
					die($e->getMessage());
				}

				if (!$photos) {
					?>

					<div class="image_not_found">
						<p>The restaurant has no images.</p>
					</div>

					<?php
				} else {
					?>
				<div id="restaurant_images">
					<div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 800px; height: 456px; overflow: hidden; visibility: hidden; background-color: #24262e;">
						<!-- Loading Screen -->
						<div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
							<div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
							<div style="position:absolute;display:block;background:url('resources/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
						</div>
						<div data-u="slides"
							 style="cursor: default; position: relative; top: 0px; left: 0px; width: 800px; height: 356px; overflow: hidden;">
							<?php
							for ($i = 0; $i < count($photos); $i++) {
								?>
								<div data-p="144.50">
									<img data-u="image" src="<?= $photos[$i]['originalPhoto'] ?>"/>
									<img data-u="thumb" src="<?= $photos[$i]['smallPhoto'] ?>"/>
								</div>

							<?php } ?>
						</div>
						<!-- Thumbnail Navigator -->
						<div data-u="thumbnavigator" class="jssort01"
							 style="position:absolute;left:0px;bottom:0px;width:800px;height:100px;"
							 data-autocenter="1">
							<!-- Thumbnail Item Skin Begin -->
							<div data-u="slides" style="cursor: default;">
								<div data-u="prototype" class="p">
									<div class="w">
										<div data-u="thumbnailtemplate" class="t"></div>
									</div>
									<div class="c"></div>
								</div>
							</div>
							<!-- Thumbnail Item Skin End -->
						</div>
						<!-- Arrow Navigator -->
						<span data-u="arrowleft" class="jssora05l" style="top:158px;left:8px;width:40px;height:40px;"></span>
						<span data-u="arrowright" class="jssora05r" style="top:158px;right:8px;width:40px;height:40px;"></span>
					</div>
				</div>
					<?php
				}

				?>
				<div class="restaurant">
						<h2>Information</h2>
					<div class="restaurant_info">
						<ul>
							<li><span>Name: </span><?= $restaurant_info['name'] ?></li>
							<li><span>City: </span><?= $restaurant_info['city'] ?></li>
							<li><span>Address: </span><?= $restaurant_info['address'] ?></li>
							<li><span>Phone Number: </span><?= $restaurant_info['phone'] ?></li>
							<li><span>Average Price:</span> <?= $restaurant_info['price'] ?></li>
							<li><span>Description: </span><?= $restaurant_info['description'] ?></li>
							<?php
								if($rating['average'] != NULL)
								{
							?>
								<li><span>Average Rating: </span>
									<img id="restaurant_rating_img" src="resources/stars/<? echo round($rating['average']); ?>stars.png" alt="rating">
								</li>
								<?php
								}
								else
								{
									?>
									<li>Average Rating: No rating available</li>
									<?php
								}
								?>
						</ul>
					</div>
					<?php

					if($isOwner)
					{
						?>
						<a href="editRestaurant.php">Edit restaurant</a>
						<?php
					}

					?>

				</div>
				<div class="map_title">
					<h2>Location</h2>
				</div>
				<div id="map" style="width:100%;height:500px"></div>

				<script>
					function myMap() {
						var mapCanvas = document.getElementById("map");
						var mapOptions = {
							center: new google.maps.LatLng(<?= $restaurant_info['latitude'] ?>, <?= $restaurant_info['longitude'] ?> ),
							zoom: 15
						}
						var map = new google.maps.Map(mapCanvas, mapOptions);
					}
				</script>

				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLM3TGQkqg8bOe9u_91uYNPEN0lT0PNhs&callback=myMap"></script>

				<?php


				try {
					$reviews = getReviews($db, $id);
					$sessionUserReview = getUserReview($db, $username, $id);
				} catch (PDOException $e) {
					die($e->getMessage());
				}
				?>
				<div class="review_title">
					<h2>Reviews</h2>
				</div>

				<?php
				if (!$reviews) {
					?>
					<div class="no_reviews">
						There aren't at the moment reviews available!
					</div>

					<?php
				} else {

					for ($i = 0; $i < count($reviews); $i++) {
						try {
							$userReview = getCurrentUserInfo($db, $reviews[$i]['user']);
						} catch (PDOException $e) {
							die($e->getMessage());
						}
						?>
						<div class="review" id="review_reply">
							<div class="review-avatar">
								<img id="review_avatar_img" src=<?= $userReview['photo'] ?> alt="avatar">
								<div class="review-user">
									<a href="user.php?username=<?= $reviews[$i]['user']; ?>"><?= $reviews[$i]['user']; ?></a>
								</div>
							</div>
							<div class="review-score">
								<span>Rating</span> <br>
								<img id="rating_img" src="resources/stars/<? echo $reviews[$i]['score']; ?>stars.png"
									 alt="rating">
							</div>
							<div class="review-text">
								<span>Comment </span><br><?= $reviews[$i]['text']; ?>
							</div>
						</div>
						<?php
						if ($isOwner) {
							if ($reviews[$i]['owner'] == NULL) {
								?>
								<div class="restaurant_reply">
									<label>Reply: </label><br>
									<form method="POST" action="action_reply_review.php" id="restaurant_reply_form">
										<textarea id="review_reply" class="text" cols="80" rows="3" maxlength="500"
												  name="review_reply"></textarea>
										<input type="hidden" name="reviewer" value="<?= $reviews[$i]['user'] ?>">
										<input type="hidden" name="restaurant" value="<?= $id ?>">
										<input type="submit" value="Reply" id="submit" name="submit">
									</form>
								</div>

								<?php
							}
						}
						if ($reviews[$i]['owner'] != NULL) {
							try {
								$ownerInformation = getCurrentUserInfo($db, $reviews[$i]['owner']);
							} catch (PDOException $e) {
								die($e->getMessage());
							}
							?>
							<div class="review" id="owner_reply">
								<div class="owner_reply_avatar">
									<img id="owner_reply_avatar" src=<?= $ownerInformation['photo'] ?> alt="avatar">
									<div class="owner_reply_user">
										<a href="user.php?username=<?= $reviews[$i]['owner']; ?>"><?= $reviews[$i]['owner']; ?></a>
									</div>
								</div>
								<div class="owner_reply_answer">
									<span>Answer </span><br> <?= $reviews[$i]['answer']; ?>
								</div>
							</div>
							<?php
						}
					}
					?>

					<span id="comment_review_reply_span"></span>
					<?php
				}
				if(!$isOwner) {
					if (!$sessionUserReview) {

						?>

						<div class="restaurant_review">
							<label>Comment (Optional): </label><br>
							<textarea id="comment_review" class="text" cols="80" rows="8" maxlength="500"
									  name="comment_review" form="restaurant_review_form"></textarea>
							<form method="POST" action="action_review.php" id="restaurant_review_form" enctype="multipart/form-data">
								<label>Rating: </label><br>
								<div class="rate">
									<input class="star star-5" id="star-5" type="radio" name="star" value="5" required/>
									<label class="star star-5" for="star-5"></label>
									<input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
									<label class="star star-4" for="star-4"></label>
									<input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
									<label class="star star-3" for="star-3"></label>
									<input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
									<label class="star star-2" for="star-2"></label>
									<input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
									<label class="star star-1" for="star-1"></label>
								</div>
								<br>
								<label>Image (Optional): </label><br>
								<input type="file" name="image" accept="image/*"><br><br>
								<input type="hidden" name="restaurant" value="<?= $id ?>">
								<input type="submit" value="Submit" id="submit" name="submit">
							</form>
						</div>

						<?php
					}
				}
			}
		}
		if($photos) {
			?>
			<script type="text/javascript">jssor_1_slider_init();</script>
			<?php
		}
	}
	else
	{
		include ('templates/no_login.php');
	}

	include ('templates/footer.php');
?>


