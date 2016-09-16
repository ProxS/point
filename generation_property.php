<?php

define('MIN_A', 0);
define('MAX_A', 10);
define('MIN_W', -10);
define('MAX_W', 10);

function generation ($created) {
    
    $an = rand(MIN_A, MAX_A);
    $at = rand(MIN_A, MAX_A);
    $ang = rand(MIN_W, MAX_W);
    
    $query = "Insert into points_property values($an,$at,$created,$ang)";
    $result = mysql_query($query) or die('Error' . mysql_error());
    
}

function countGenegation ($created) {
    
    $connection = mysql_connect('localhost', 'root', '')
	    or die('Ошибка соединения: ' . mysql_error());

    mysql_select_db('point', $connection)
	    or die('Could not select database.');
    
    $createdstep = $created;
    $count = 50;
    
    for($i = 0; $i < $count; $i++) {
	generation($createdstep);
	$createdstep += 1000;
    }
    
    mysql_close($connection);
}

countGenegation(microtime(true)*1000);
