<?php
$width = 100;               //Ширина изображения

$height = 60;               //Высота изображения

$font_size = 16;            //Размер шрифта

$let_amount = 4;            //Количество символов, которые нужно набрать

$fon_let_amount = 30;       //Количество символов на фоне

$font = "fonts/cour.ttf";   //Путь к шрифту

$letters = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','9');
$colors = array('10','30','50','70','90','110','130','150','170','190','210');

session_start(); 

$src = imagecreatetruecolor($width,$height);    //создаем изображение              

$fon = imagecolorallocate($src,255,255,255);    //создаем фон

imagefill($src,0,0,$fon);                       //заливаем изображение фоном

//добавляем на фон буковки
for($i=0;$i < $fon_let_amount;$i++) {
	//случайный цвет
	$color = imagecolorallocatealpha($src,rand(0,255),rand(0,255),rand(0,255),100);
	//случайный символ
	$letter = $letters[rand(0,sizeof($letters)-1)];
	//случайный размер                             
	$size = rand($font_size-2,$font_size+2);                                           
	imagettftext($src,$size,rand(0,45),
	rand($width*0.1,$width-$width*0.1),
	rand($height*0.2,$height),$color,$font,$letter);
}

//то же самое для основных букв
for($i=0;$i < $let_amount;$i++) {
	$color = imagecolorallocatealpha($src,$colors[rand(0,sizeof($colors)-1)],
	$colors[rand(0,sizeof($colors)-1)],
	$colors[rand(0,sizeof($colors)-1)],rand(20,40));
	$letter = $letters[rand(0,sizeof($letters)-1)];
	$size = rand($font_size*2-2,$font_size*2+2);
	$x = ($i+1)*$font_size + rand(1,5);      //даем каждому символу случайное смещение
	$y = (($height*2)/3) + rand(0,5);                           
	$cod[] = $letter;                        //запоминаем код
	imagettftext($src,$size,rand(0,15),$x,$y,$color,$font,$letter);
}
$cod = implode("",$cod);                    //переводим код в строку
$_SESSION['rand_code'] = $cod;
header ("Content-type: image/jpg");         //выводим готовую картинку
imagegif($src);
	
?>
