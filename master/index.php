<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Home";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	include_once('database/connection.php');
	include_once('database/queries.php');

	try {
		$top_restaurants = getTopRestaurants($db);
	} catch (PDOException $e) {
		die($e->getMessage());
	}

	if($top_restaurants)
	{
	?>
		</div>
	<script type="text/javascript" src="scripts/index.js"></script>

	<link rel="stylesheet" type="text/css" href="css/jssor_index.css">
	<script type="text/javascript" src="scripts/jssor_index.js"> </script>


		<div style="position: relative; width: 100%; overflow: hidden;">

				<div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden; visibility: hidden;">
					<!-- Loading Screen -->
					<div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
						<div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
						<div style="position:absolute;display:block;background:url('resources/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
					</div>
					<div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
						<?php
						for ($i = 0; $i < count($top_restaurants) && $i < 5; $i++) {
							try {
								$restaurant_info = getRestaurantById($db, $top_restaurants[$i]['restaurant']);
								$restaurant_images = getRestaurantPhotos($db, $top_restaurants[$i]['restaurant']);
							} catch (PDOException $e) {
								die($e->getMessage());
							}
							?>

							<div data-p="225.00">
								<a href="restaurant.php?id=<? echo $top_restaurants[$i]['restaurant']?>">
									<img data-u="image" src="<?= $restaurant_images[rand(0, count($restaurant_images)-1)]['originalPhoto'] ?>"/>
								</a>
								<div style="background: rgba(169, 169, 169, 0.5);position:absolute;top:400px;left:30px;width:auto;height:auto;z-index:0;font-size:30px;color:#ffffff;line-height:38px;">
									<?php echo $restaurant_info['name']?> ➤ <?php echo round($top_restaurants[$i]['average'],1);?>☆
								</div>
							</div>

							<?php
						}
						?>
					</div>
					<!-- Bullet Navigator -->
					<div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
						<!-- bullet navigator item prototype -->
						<div data-u="prototype" style="width:16px;height:16px;"></div>
					</div>
					<!-- Arrow Navigator -->
					<span data-u="arrowleft" class="jssora22l" style="top:0px;left:8px;width:40px;height:58px;" data-autocenter="2"></span>
					<span data-u="arrowright" class="jssora22r" style="top:0px;right:8px;width:40px;height:58px;" data-autocenter="2"></span>
				</div>
		</div>

	<script type="text/javascript">jssor_1_slider_init();</script>
<div>
<?php
	}
	else
	{
		try {
			$restaurants = getRestaurants($db);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
		if($restaurants)
		{
			?>
			</div>
			<script type="text/javascript" src="scripts/index.js"></script>

			<link rel="stylesheet" type="text/css" href="css/jssor_index.css">
			<script type="text/javascript" src="scripts/jssor_index.js"> </script>

			<div style="position: relative; width: 100%; overflow: hidden;">
				<div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden; visibility: hidden;">
					<!-- Loading Screen -->
					<div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
						<div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
						<div style="position:absolute;display:block;background:url('resources/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
					</div>
					<div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
						<?php
						for ($i = 0; $i < count($restaurants) && $i < 5; $i++) {
							try {
								$restaurant_images = getRestaurantPhotos($db, $restaurants[rand(0, count($restaurants)-1)]['id']);
							} catch (PDOException $e) {
								die($e->getMessage());
							}
							?>

							<div data-p="225.00">
								<a href="restaurant.php?id=<? echo $restaurants[rand(0, count($restaurants)-1)]['id']?>">
									<img data-u="image" src="<?= $restaurant_images[rand(0, count($restaurant_images)-1)]['originalPhoto'] ?>"/>
								</a>
								<div style="background: rgba(169, 169, 169, 0.5);position:absolute;top:400px;left:30px;width:auto;height:auto;z-index:0;font-size:30px;color:#ffffff;line-height:38px;">
									<?php echo $restaurants['name']?>
								</div>
							</div>

							<?php
						}
							?>
					</div>
					<!-- Bullet Navigator -->
					<div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
						<!-- bullet navigator item prototype -->
						<div data-u="prototype" style="width:16px;height:16px;"></div>
					</div>
					<!-- Arrow Navigator -->
					<span data-u="arrowleft" class="jssora22l" style="top:0px;left:8px;width:40px;height:58px;" data-autocenter="2"></span>
					<span data-u="arrowright" class="jssora22r" style="top:0px;right:8px;width:40px;height:58px;" data-autocenter="2"></span>
				</div>
			</div>

		<script type="text/javascript">jssor_1_slider_init();</script>
		<div>
			<?php
		}
		else {
			?>
			<div class="no_restaurants">
				There aren't at the moment restaurants available!
			</div>
			<?php
		}
	}
	
	include ('templates/footer.php');

?>


