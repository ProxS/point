<?php

function generation ($created) {
    
    $an = rand(0, 20);
    $at = rand(0, 20);
    $ang = rand(0, 360);
    
    $query = "Insert into points_property values($an,$at,$created,$ang)";
    $result = mysql_query($query) or die('Error' . mysql_error());
    
}

