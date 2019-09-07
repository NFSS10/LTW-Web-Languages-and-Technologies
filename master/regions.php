<?php

    include_once('database/connection.php');
    include_once('database/queries.php');

    $cities = getCity($db);

    $result = array();
    foreach ($cities as $region)
        $result[] = $region['city'];

    echo json_encode($result);
?>
