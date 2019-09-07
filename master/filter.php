<form method="POST" action="list_restaurants.php" id="filter_form">
    <div class="filter">
            <select class="sel_filter_1" name="filter_sel_reg">
            </select>

            <select class="sel_filter_2" name="filter_sel_rating">
                    <option value="Any">Rating(Any)</option>
                    <?php
                        for($k = 1; $k < 6; $k++)
                        {
                                if($postSet) { ?>
                                        <option  <?php if($rating == $k)  echo "selected ='SELECTED'";?>  value=<?= $k?>><?= $k?></option> <?php }
                                else {?>
                                        <option value=<?= $k?>><?= $k?></option> <?php }
                        }
                    ?>
            </select>
            <label>Price Min: </label>
            <input type="number" name="pricemin" id="pricemin" value=<?=$minPrice['p']?> min=<?=$minPrice['p']?> max=<?=$maxPrice['p']?> step="0.1">
            <label>Price Max: </label>
            <input type="number" name="pricemax" id="pricemax" value=<?=$maxPrice['p']?> min=<?=$minPrice['p']?> max=<?=$maxPrice['p']?> step="0.1"><span id="maxvalue"></span><br>
            <input class="f_button" type="submit" name="search_filter" value="Search"/>
            <button class="f_button_reset" type="button">Reset</button>

    </div>
</form>

<script type="text/javascript" src="scripts/filter.js"> </script>
