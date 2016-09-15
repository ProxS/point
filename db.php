<?php

//получаем данные на основе предыдущих 0 SQL
function GetNext($vx0, $vy0, $posx0, $posy0, $an, $at, $ang,$dt) {
    $a_total = sqrt($at * $at + $an * $an);

//convert градусов в радианы
    $rad = $ang * M_PI / 180;

//https://yandex.ru/images/search?text=%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%86%D0%B8%D1%8F%20%D0%B2%D0%B5%D0%BA%D1%82%D0%BE%D1%80%D0%B0%20%D1%83%D1%81%D0%BA%D0%BE%D1%80%D0%B5%D0%BD%D0%B8%D1%8F%20%D0%BD%D0%B0%20%D0%BE%D1%81%D1%8C&noreask=1&img_url=http%3A%2F%2Fmognovse.ru%2Fmogno%2F975%2F974870%2F974870_html_2d039d81.jpg&pos=10&rpt=simage&lr=213    
    $ax = $a_total * cos($rad);
    $ay = $a_total * sin($rad);

    $vx = $vx0 + $ax * $dt;
    $vy = $vy0 + $ay * $dt;

    $posx = $posx0 + $vx0 * $t + ($ax * $dt * $dt) / 2;
    $posy = $posy0 + $vy0 * $t + ($ay * $dt * $dt) / 2;


    return array('vx' => $vx, 'vy' => $vy, 'posx' => $posx, 'posy' => $posy, 'ang' => $ang);
//    var_dump($vx, $vy, $posx, $posy, $ang);
}

//получаем данные на указанное время 2 SQL
function GetPrev($created) {

//хранить дату минуты сек в  поле инт 8
//если записи нет в теч мин то сгенерировать
    $query = "Select * from points where created <= $created  and created >= $created-60*1000 ORDER BY created desc limit 1";
    $result = mysql_query($query) or die('Error' . mysql_error());
    $line = mysql_fetch_array($result);
    
    $query_a = "Select * from points_property where created <= $created ORDER BY created desc limit 1";
    $result_a = mysql_query($query_a) or die('Error' . mysql_error());
    $line_a = mysql_fetch_array($result_a);
    
    if (empty($line)) {

	$vx = rand(-50, 50);
	$vy = rand(-50, 50);
	$posx = rand(-10, 10);
	$posy = rand(-10, 10);
	$created = microtime(true)*1000;
	$ang = rand(0, 360);
	$an = $line_a['an'];
	$at = $line_a['at'];
	$ang = $line_a['ang'];

	$query = "Insert into points(vx,vy,posx,posy,created,ang) values($vx,$vy,$posx,$posy,$created,$ang)";
	$result = mysql_query($query) or die('Error' . mysql_error());
	
//	var_dump(array($vx, $vy, $posx, $posy, $created, $an, $at, $ang));
	return array('vx' => $vx, 'vy' => $vy, 'posx' => $posx, 'posy' => $posy, 'created' => $created, 'an' => $an, 'at' => $at, 'ang' => $ang);
    } else {
	$vx = $line['vx'];
	$vy = $line['vy'];
	$posx = $line['posx'];
	$posy = $line['posy'];
	$created = $line['created'];
	$an = $line_a['an'];
	$at = $line_a['at'];
	$ang = $line_a['ang'];
	
//	var_dump(array($vx, $vy, $posx, $posy, $created, $an, $at, $ang));
	return array('0' => $vx, 'vy' => $vy, 'posx' => $posx, 'posy' => $posy, 'created' => $created, 'an' => $an, 'at' => $at, 'ang' => $ang);
    }
}

//по обращению получение данных на экран
//заполнение проперти проверка ограничений vx vy posx posy
//заполнение данных по ускорению отдельно во 2 файле

function getPoint($created) {
    include 'generation_property.php';
    
    $connection = mysql_connect('localhost', 'root', '')
	    or die('Ошибка соединения: ' . mysql_error());

    mysql_select_db('point', $connection)
	    or die('Could not select database.');
    
    generation($created);
    $list = GetPrev($created);
    
    $dt = ($created - $list['created'])/1000 ;
    
    $next = GetNext($list['vx'], $list['vy'], $list['posx'], $list['posy'], $list['an'], $list['at'], $list['ang'], $dt);
    
    var_dump($next);

    mysql_close($connection);
}
//var_dump(microtime(true)*1000);
getPoint(microtime(true)*1000);

