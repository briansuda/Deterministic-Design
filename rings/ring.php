<?php
/*
Brian Suda
brian@suda.co.uk

This will take an image and parse through all the pixels and attempt to make a nice circular representation.

This is an idea similar to http://hint.fm/projects/wired2008/

More about the process can be read about on http://netmagazine.com/tutorials/create-svg-data-visualisation-php

Usage:
php ring.php > output.svg
*/

// Fetch the JPEG image from a file
$im = imagecreatefromjpeg("example.jpg");

// use the built-in color palette reducer instead of the reduceColors()
// imagetruecolortopalette($im,true,20);

// Get the height and width based on the x,y values
$x = imagesx($im);
$y = imagesy($im);

$rgb = array();
$counter = 0;
$scaler = 30;

// get colour count frequency by looping through the image column by column
for($i=0;$i<$x;$i++){
  for($j=0;$j<$y;$j++){
	// get that pixel's RGB value and store it in an array
    $rgb[imagecolorat($im, $i, $j)]++;
  }
}

// Sort the array
asort($rgb);

// We don't need the source image any more
imagedestroy($im);

// Using our own color merging algorithm, we'll find nearly the same value color and merged them
$rgb = reduceColors($rgb);

header('Content-Type: image/svg+xml');

echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';

// Set the center
$c = (int)(($x*$y)/$scaler);
$prev = 0;

foreach($rgb as $k=>$v){
  if($v > 0) {
    $r = ($k >> 16) & 0xFF;
    $g = ($k >> 8) & 0xFF;
    $b = $k & 0xFF;

	// convert each RGB value into a hex color
    $hex = str_pad(dechex($r),2,'0',STR_PAD_LEFT).str_pad(dechex($g),2,'0',STR_PAD_LEFT).str_pad(dechex($b),2,'0',STR_PAD_LEFT);

	// stack the circles onto of one another as they gradually get smaller
    echo '<circle cx="'.$c.'" cy="'.$c.'" r="'.($c-$prev).'" fill="#'.$hex.'" />'."\n";
    $prev += (int)($v/$scaler);

  }
}

echo '</svg>';

function reduceColors($rgb){
	// if you want more rings, adjust the $plusminus to taste
	$plusminus = 50;
	arsort($rgb);	
	$temp = array();
	// do colour merger
	foreach($rgb as $k=>$v){
	  if($v != 0){
	    $r = ($k >> 16) & 0xFF;
	    $g = ($k >> 8) & 0xFF;
	    $b = $k & 0xFF;
		
		$matched = false;
	    foreach($temp as $m=>$n){
	      if($m != $k){
	        $rs = ($m >> 16) & 0xFF;
	        $gs = ($m >> 8) & 0xFF;
	        $bs = $m & 0xFF;

	        if (
	          ($rs <= ($r+$plusminus))&&($rs >= ($r-$plusminus)) &&
	          ($gs <= ($g+$plusminus))&&($gs >= ($g-$plusminus)) &&
	          ($bs <= ($b+$plusminus))&&($bs >= ($b-$plusminus)) &&
			  $matched == false
	          ) {
	  				$temp[$m] += $v;
					$matched = true;
	        }
	      }
	    }
		if(!($matched)){
			$temp[$k] = $v;
		}
	  }
	}

	asort($temp);
	return $temp;
}

?>