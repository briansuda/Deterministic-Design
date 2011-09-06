<?php
/*
Brian Suda
brian@suda.co.uk

This is very similar to dots-bw.php it takes the luminosity from the source image and made a dot representing the pixel, but this time the resulting color output is random.

Usage:
php dots-random.php > dots-random.svg

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


$stepper = 10;

// Loop through each pixel and make it a dot
for($i=0;$i<$x;$i++){
  for($j=0;$j<$y;$j++){
	$k = imagecolorat($im, $i, $j);
	$rad = ($stepper/2);
	
	$r = ($k >> 16) & 0xFF;
    $g = ($k >> 8) & 0xFF;
    $b = $k & 0xFF;

	// set the radius larger or smaller depending on darkness of the pixel
	$l = rgb2lum(array($r,$g,$b));	
	
	$rad = $rad-($rad*$l);

	$r = rand(0,255);
	$g = rand(0,255);
	$b = rand(0,255);

    $hex = str_pad(dechex($r),2,'0',STR_PAD_LEFT).str_pad(dechex($g),2,'0',STR_PAD_LEFT).str_pad(dechex($b),2,'0',STR_PAD_LEFT);

	echo '<circle cx="'.($i*$stepper).'" cy="'.($j*$stepper).'" r="'.$rad.'" fill="#'.$hex.'" />';
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
