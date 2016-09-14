<?php

function connect() {

    $conn_string = "dbname=grid user=postgres password=123456";
    $dbconn3 = pg_connect($conn_string)
	    or die('Could not connect: ' . pg_last_error());
}

function draw_axises($im_width, $im_heignt) {
    global $im, $black, $l_grey, $x0, $y0, $maxX, $maxY;
    $x0 = 25.0; //начало оси координат по X
    $y0 = 20.0; //начало оси координат по Y
    $maxX = $im_width - $x0; //максимальное значение оси
//координат по X в пикселах
    $maxY = $im_heignt - $y0; //максимальное значение оси
//координат по Y в пикселах
//рисуем ось X
    imageline($im, $x0, $maxY, $maxX, $maxY, $black);
//рисуем ось Y
    imageline($im, $x0, $y0, $x0, $maxY, $black);
//рисуем стрелку на оси X
    $xArrow[0] = $maxX - 6;
    $xArrow[1] = $maxY - 2;
    $xArrow[2] = $maxX;
    $xArrow[3] = $maxY;
    $xArrow[4] = $maxX - 6;
    $xArrow[5] = $maxY + 2;
    imagefilledpolygon($im, $xArrow, 3, $black);
//рисуем стрелку на оси Y
    $yArrow[0] = $x0 - 2;
    $yArrow[1] = $y0 + 6;
    $yArrow[2] = $x0;
    $yArrow[3] = $y0;
    $yArrow[4] = $x0 + 2;
    $yArrow[5] = $y0 + 6;
    imagefilledpolygon($im, $yArrow, 3, $black);
}

function draw_grid($xStep, $yStep, $xCoef, $yCoef) {
    global $im, $black, $l_grey, $x0, $y0, $maxX, $maxY;
    $xSteps = ($maxX - $x0) / $xStep - 1; //определяем количество
//шагов по оси X
    $ySteps = ($maxY - $y0) / $yStep - 1; //определяем количество
//шагов по оси Y
//выводим сетку по оси X
    for ($i = 1; $i < $xSteps + 1; $i++) {
	imageline($im, $x0 + $xStep * $i, $y0, $x0 + $xStep * $i, $maxY - 1, $l_grey);
	//при необходимости выводим значения линий сетки по оси X
	imagestring($im, 1, ($x0 + $xStep * $i) - 1, $maxY + 2, $i * $xCoef, $black);
    }
//выводим сетку по оси Y
    for ($i = 1; $i < $ySteps + 1; $i++) {
	imageline($im, $x0 + 1, $maxY - $yStep * $i, $maxX, $maxY - $yStep * $i, $l_grey);
	//при необходимости выводим значения линий сетки по оси Y
	imagestring($im, 1, 0, ($maxY - $yStep * $i) - 3, $i * $yCoef, $black);
    }
}

function draw_data($data_x, $data_y, $points_count, $color) {
    global $im, $x0, $y0, $maxY, $scaleX, $scaleY;
    for ($i = 1; $i < $points_count; $i++) {
	//рисуем линейный график по точкам из массивов данных
	imageline($im, $x0 + $data_x[$i - 1] * $scaleX, $maxY - $data_y[$i - 1] * $scaleY, $x0 + $data_x[$i] * $scaleX, $maxY - $data_y[$i] * $scaleY, $color);
    }
}

//создаем рисунок шириной 500 и высотой 400 пикселов
$im = @ImageCreate(500, 400);
$white = ImageColorAllocate($im, 255, 255, 255);
$black = ImageColorAllocate($im, 0, 0, 0);
$red = ImageColorAllocate($im, 255, 0, 0);
$green = ImageColorAllocate($im, 0, 255, 0);
$blue = ImageColorAllocate($im, 0, 0, 255);
$yellow = ImageColorAllocate($im, 255, 255, 0);
$magenta = ImageColorAllocate($im, 255, 0, 255);
$cyan = ImageColorAllocate($im, 0, 255, 255);
$l_grey = ImageColorAllocate($im, 221, 221, 221);
//рисуем оси координат
draw_axises(500, 400);
//задаем массивы данных графиков
connect();
$query = 'SELECT * FROM points order by id asc';
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$line = pg_fetch_all($result);


//объединяем данные из массивов данных
//для вычисления масштаба
$x = array_merge($x1);
$y = array_merge($y1);
//получаем максимальные значения
//элементов для каждого массива
$maxXVal = max($x);
$maxYVal = max($y);
//вычисляем масштаб преобразования данных
//в координаты рабочей области
$scaleX = ($maxX - $x0) / $maxXVal;
$scaleY = ($maxY - $y0) / $maxYVal;
//задаем шаг для координатной сетки в пикселах
$xStep = 30;
$yStep = 30;
//рисуем координатную сетку
draw_grid($xStep, $yStep, round($xStep / $scaleX, 1), round($yStep / $scaleY, 1), true);
//рисуем первый график
draw_data($x1, $y1, 4, $red);
//рисуем второй график
draw_data($x2, $y2, 4, $blue);
//выводим рисунок
Header("Content-Type: image/png");
ImagePNG($im);
//освобождаем занимаемую рисунком память
imagedestroy($im);
?>