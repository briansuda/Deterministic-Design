<?php
/*
Brian Suda
brian@suda.co.uk

Santa Monica shoes did a series of posters of famous faces in this pixelized design. Using their logo instead of dots, they made a two pass, pattern. One large stars equally spaces, and a second pass of smaller stars in between that formed the design.

This is a quick mock-up of something similar. It needs some work. It is twice as wide as it is tall due to the shifted, offset design.

Usage:
php stars-bw.php > stars.svg

*/
header('Content-Type: image/svg+xml');

echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';


$im = imagecreatefromjpeg("example.jpg");
$x = imagesx($im);
$y = imagesy($im);
// $rgb = array();

$stepper = 5;
$padding = 5;
$offset  = 10;

// Loop through each pixel and make it a small star or not?
for($k=0;$k<$x;$k++){ // columns
  for($l=0;$l<$y;$l++){ // rows
	
	if($l%2 == 1){
		$i = $k + 0.5;
	} else {
		$i = $k;
	}

	$j = $l;
	$m = imagecolorat($im, $k, $l);
	$rad = ($stepper/2);

    $r = ($m >> 16) & 0xFF;
    $g = ($m >> 8) & 0xFF;
    $b = $m & 0xFF;

	$lum = rgb2lum(array($r,$g,$b));

	// Uses the luminosity to make shades of grey
	$rgb = array($lum*255,$lum*255,$lum*255);
	$r = $rgb[0];
	$g = $rgb[1];
	$b = $rgb[2];
    $hex = str_pad(dechex($r),2,'0',STR_PAD_LEFT).str_pad(dechex($g),2,'0',STR_PAD_LEFT).str_pad(dechex($b),2,'0',STR_PAD_LEFT);
	
	echo '<path fill="#'.$hex.'" d="M'.(($i*$offset)+($padding*$i)+($padding*1.5)).','.(($j*$offset)+($padding/2)).'	
	c0,0,0,'.($stepper/2).','.($stepper/2).','.($stepper/2).'
	c0,0-'.($stepper/2).',0-'.($stepper/2).','.($stepper/2).'
	c0,0-0-'.($stepper/2).'-'.($stepper/2).'-'.($stepper/2).'
	C'.((($i*$offset)+($padding*$i)+($padding*1.5))-($stepper/2)).','.(($stepper/2)+(($j*$offset)+($padding/2))).','.(($i*$offset)+($padding*$i)+($padding*1.5)).','.(($stepper/2)+(($j*$offset)+($padding/2))).','.(($i*$offset)+($padding*$i)+($padding*1.5)).','.(($j*$offset)+($padding/2)) .'z"/>'."\n";
    echo "\n";
    
  }
}

$stepper = 10;
$padding = 5;

// Loop through each pixel and make a big star!
for($k=0;$k<$x;$k++){ // columns
  for($l=0;$l<$y;$l++){ // rows
	
	if($l%2 == 1){
		$i = $k + 0.5;
	} else {
		$i = $k;
	}

	$j = $l;
	
	echo '<path d="M'.(($i*$stepper)+($padding*$i)).','.(($j*$stepper)).'	
	c0,0,0,'.($stepper/2).','.($stepper/2).','.($stepper/2).'
	c0,0-'.($stepper/2).',0-'.($stepper/2).','.($stepper/2).'
	c0,0-0-'.($stepper/2).'-'.($stepper/2).'-'.($stepper/2).'
	C'.((($i*$stepper)+($padding*$i))-($stepper/2)).','.(($stepper/2)+(($j*$stepper))).','.(($i*$stepper)+($padding*$i)).','.(($stepper/2)+(($j*$stepper))).','.(($i*$stepper)+($padding*$i)).','.(($j*$stepper)) .'z"/>'."\n";
    echo "\n";
    
  }
}


echo '</svg>';
function rgb2lum($rgb){
	$rgb[0] = $rgb[0]/255;
	$rgb[1] = $rgb[1]/255;
	$rgb[2] = $rgb[2]/255;

    $max = max($rgb);
    $min = min($rgb);

	// Luminosity
    return ($max + $min) / 2;

}
?>
