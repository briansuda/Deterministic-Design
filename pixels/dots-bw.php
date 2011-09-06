<?php
/*
Brian Suda
brian@suda.co.uk

This takes in an image and loops through all the pixels to emulate a dot pattern you see in news print. The size of the dot is determined by the luminosity of the source color.

The output can be in pure black or shades of grey

Usage:
php dots-bw.php > bw.svg

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

	// Uses the luminosity to make shades of grey
	$rgb = array($l*255,$l*255,$l*255);
	$r = $rgb[0];
	$g = $rgb[1];
	$b = $rgb[2];
    $hex = str_pad(dechex($r),2,'0',STR_PAD_LEFT).str_pad(dechex($g),2,'0',STR_PAD_LEFT).str_pad(dechex($b),2,'0',STR_PAD_LEFT);
	
	// OR Pure Black, different sizes to emulate shades of grey
	$hex = '000000';
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
