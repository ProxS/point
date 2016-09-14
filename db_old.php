<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

function connect() {

    $conn_string = "dbname=grid user=postgres password=123456";
    $dbconn3 = pg_connect($conn_string)
	    or die('Could not connect: ' . pg_last_error());
}

function get_point() {
    connect();
    $query = 'SELECT * FROM points order by created desc limit 1';
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    $line = pg_fetch_array($result, null, PGSQL_ASSOC);
    
    $a_add = rand(0, 100);
    $at_add = rand(0, 100);
    $created = time();
    $c_m = gettimeofday();
    $created_m = $c_m['usec'];
    var_dump($created_m);
	    
    $query_time = "Insert into points_property(an,created,at) values($a_add,$created,$at_add)";
    $result3 = pg_query($query_time) or die('Ошибка запроса: ' . pg_last_error());
    
    $vx0 = $line['vx'];
    $vy0 = $line['vy'];
    $posx0 = $line['posx'];
    $posy0 = $line['posy'];
    $angl0 = $line['angl'];
        
    //угловое ускорение
    $a = rand(0, 100);
    //нормальное ускорение
    $a_default = rand(0, 100);
    // delta t
    $t = 1;
    
    //определение скорости
    $vx = $vx0 + $a*$t;
    $vy = $vy0 + $a*$t;
    
    $a_all = sqrt($a*$a+$a_default*$a_default);

    if($vx>$vx0 && $vy>$vy0){
	$posx = $posx0 + $vx0*$t + ($a*$t*$t)/2;
	$posy = $posy0 + $vy0*$t + ($a*$t*$t)/2;
    }
    elseif($vx < $vx0 || $vy<$vy0){
	$posx = $posx0 + $vx*$t - ($a*$t*$t)/2;
	$posy = $posy0 + $vy0*$t - ($a*$t*$t)/2;
    }
    else {
	$posx = $posx0 + $vx0*$t + ($a*$t*$t)/2;
	$posy = $posy0 + $vy0*$t + ($a*$t*$t)/2; 
    }
    
        
    $result2 = new ArrayObject();
    $result2->posx = $posx;
    $result2->posy = $posy;
    $result2->vx = $vx;
    $result2->vy = $vy;
    $result2->created = $created;
    $result2->created_m = $created_m;
    
    
    
    
    $query1 = "Insert into points(vx,vy,posx,posy,created,created_m) values($vx,$vy,$posx,$posy,$created,$created_m)";
    $result1 = pg_query($query1) or die('Ошибка запроса: ' . pg_last_error());
    $line2 = pg_fetch_array($result1, null, PGSQL_ASSOC);

}
function start(){
    
    $k = 1;
    for($i = 0;$i<$k;$i++){
	get_point();
    }
}
start();
