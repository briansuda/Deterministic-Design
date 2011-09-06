<?php
/*
Brian Suda
brian@suda.co.uk

This loops through the image and outputs one dot for each pixel in the image. Avoid using large images due to performance reasons. The radius of each output dot is determined by the luminosity of the source pixels.

Usage:
php dots.php > dots.svg

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

	$l = rgb2lum(array($r,$g,$b));
	
	$rad = $rad-($rad*$l);

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
