<?php

function connection() {
    $connection = mysql_connect('localhost', 'root', '') 
	    or die('ќшибка соединени€: ' . mysql_error());
    
    mysql_select_db('point', $connection) 
	    or die('Could not select database.');
    
    echo '≈сть connection';
}

//получаем данные на основе предыдущих 0 SQL
function GetNext($vx0, $vy0, $posx0, $posy0, $an, $at, $ang, $dt) {

    $a_total = sqrt($at * $at + $an * $an);

//https://yandex.ru/images/search?text=%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%86%D0%B8%D1%8F%20%D0%B2%D0%B5%D0%BA%D1%82%D0%BE%D1%80%D0%B0%20%D1%83%D1%81%D0%BA%D0%BE%D1%80%D0%B5%D0%BD%D0%B8%D1%8F%20%D0%BD%D0%B0%20%D0%BE%D1%81%D1%8C&noreask=1&img_url=http%3A%2F%2Fmognovse.ru%2Fmogno%2F975%2F974870%2F974870_html_2d039d81.jpg&pos=10&rpt=simage&lr=213    
    $ax = $a_total * cos($ang);
    $ay = $a_total * sin($ang);
    
    $vx = $vx0 + $ax * $dt;
    $vy = $vy0 + $ay * $dt;

    $posx = $posx0 + $vx0 * $t + ($ax * $dt * $dt) / 2;
    $posy = $posy0 + $vy0 * $t + ($ay * $dt * $dt) / 2;


//    return [$vx, $vy, $posx, $posy, $ang];
//    var_dump($vx, $vy, $posx, $posy, $ang);
}

//получаем данные на указанное врем€ 2 SQL
function GetPrev($created) {

//хранить дату минуты сек в  поле инт 8
//если записи нет в теч мин то сгенерировать
    $query = "Select * from points where created <= $created  and created >= $created-60*1000 ORDER BY created desc limit 1";
    $result = mysql_query($query) or die('Error' . mysql_error());
    $line = mysql_fetch_array($result);

//хранить в милисек
    $query_a = "Select * from points_property where created <= $created ORDER BY created desc limit 1";
    $result_a = mysql_query($query_a) or die('Error' . mysql_error());
    $line_a = mysql_fetch_array($result_a);

    $vx = $line['vx'];
    $vy = $line['vy'];
    $posx = $line['posx'];
    $posy = $line['posy'];
    $created = $line['created'];
    $created_m = $line['created_m'];
    $an = $line['an'];
    $at = $line['at'];
    $ang = $line['ang'];

//    return [$vx, $vy, $posx, $posy, $created, $an, $at, $ang];
}

//по обращению получение данных на экран
//заполнение проперти проверка ограничений vx vy posx posy

