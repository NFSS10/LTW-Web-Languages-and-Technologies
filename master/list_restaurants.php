<?php
    session_start();
    session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Restaurants";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;


	if(isset($_SESSION['login_user'])) {

        include_once('database/connection.php');
        include_once('database/queries.php');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

            $postSet = isset($_POST["search_filter"]);
            $searchFormSet = isset($_POST["search_form"]);
            if($postSet)
            {
                $region = $_POST['filter_sel_reg'];
                $rating = $_POST['filter_sel_rating'];
                $price_max = $_POST['pricemax'];
                $price_min = $_POST['pricemin'];
            }
            else if($searchFormSet)
            {
                $searched = $_POST['search'];
                $order_type = $_POST['sel_order'];
            }
        }

        try {

            if($postSet)
            {
                if($region != "Any")
                {
                 if($rating != "Any")
                        $restaurants = getRestaurantsWithSpecsRegRat($db, $region, $rating, $price_min, $price_max);
                    else
                        $restaurants = getRestaurantsWithSpecsReg($db, $region, $price_min, $price_max);
                }
                else {
                        if($rating != "Any")
                            $restaurants = getRestaurantsWithSpecsRat($db, $rating, $price_min, $price_max);

                        else
                            $restaurants = getRestaurantsWithSpecs($db, $price_min, $price_max);
                }

            }
            else if($searchFormSet)
                    $restaurants = searchRestaurantsByName($db, $searched, $order_type);
            else
                $restaurants = getRestaurants($db);


            $maxPrice = getMaxPrice($db);
            $minPrice = getMinPrice($db);

        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }

        include_once('filter.php'); ?>

		<script>
            $(getRegions);

                function getRegions() {
                $.getJSON("regions.php").done(function (regions) {

                var select = $("div.filter .sel_filter_1");
                var postSet = '<?php echo $postSet; ?>';
                var reg = '<?php echo $region; ?>' ;

                select.append("<option value='Any'> Region(Any) </option>");
                    for(var i = 0; i < regions.length; i++) {
                        if (postSet && regions[i] == reg)
                            select.append("<option value=" + regions[i] + " selected='selected' >" + regions[i] + "</option>");
                        else
                            select.append("<option value=" + regions[i] + ">" + regions[i] + "</option>");
                        }
                    });
                }
        </script>

        <?php

        for($i = 0; $i < count($restaurants); $i++)
        {

            try {
                $restaurant_img = getRestaurantPerfilImage($db, $restaurants[$i]['id']);

                if($searchFormSet)
                    $rest_name = $restaurants[$i];
                else
                    $rest_name = getRestaurantById($db,$restaurants[$i]['id']);
            }
            catch (PDOException $e)
            {
                die($e->getMessage());
            }

            ?>

            <div class="restaurant_img">
                <a target="_blank" href= <?="restaurant.php?id=".$restaurants[$i]['id'].""?>>
                    <img src=<?=$restaurant_img['mediumPhoto']?> alt=<?=$restaurants[$i]['id']?>>
                </a>
                <div class="description"><?=$rest_name['name']?></div>
            </div>
        <?php
        }

        include_once("no_results_msg.php");
    }
	else
	{
		include ('templates/no_login.php');
	}

	include ('templates/footer.php');

?>
