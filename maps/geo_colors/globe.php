<?php
/*
Brian Suda
brian@suda.co.uk

This uses the GeoNames database list of all cities with a population larger than 1,000 members. Each point is color-coded with the colorFromGeo() and placed on the map in the correct possition with it's related color.

The results is a rainbow map of the globe without the need of borders. Ideally, this can be used to find any place on the globe by its color.

You can read more online about this project at:
http://optional.is/required/2010/12/13/hls-world-map/

Usage:
php globe.php > output.svg

*/

	echo '<?xml version="1.0" standalone="no"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
	"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1"
	xmlns="http://www.w3.org/2000/svg">';
	$f = fopen('cities1000.txt','r');
	
	$counter = 0;
	
	while($line = fgetcsv($f,1000,"\t")){
		$lat = $line[4];
		$long = $line[5];
		
		
		$colour = colorFromGeo($lat,$long); // location
		echo '<circle r="1" cy="'.($lat*4).'" cx="'.($long*4).'" stroke="black" stroke-width="0" fill="#'.$colour.'"/>'."\n";			
	}
	fclose($f);
	echo '</svg>';
	
	
	
	function colorFromGeo($lat,$lon){
		
		/////////////////////////////
		// Hue
		/////////////////////////////
		// 0 - 359 
		$lon = abs(floor(($lon+180)));
		// bring it into range
		if($lon > 359) { $lon = $lon%359; }
		$h = $lon/359;

		/////////////////////////////
		// Saturation
		/////////////////////////////
		$s = 1;


		/////////////////////////////
		// Lightness
		/////////////////////////////
		$lat = abs(round(($lat+90)));

		// move it into range
		if($lat > 180) { $lat = $lat%180; }

		// make sure it is between 0 and 1
		 $l = $lat/180;
		//$l = ($lat/360)+(.25);


		$rgb = hsl2rgb($h, $s, $l);
$color = str_pad(dechex($rgb[0]),2,"0",STR_PAD_LEFT).str_pad(dechex($rgb[1]),2,"0",STR_PAD_LEFT).str_pad(dechex($rgb[2]),2,"0",STR_PAD_LEFT);

		return $color;
}

function hsl2rgb($h,$s,$l) {
	// if there is no saturation, everything is grey
	if($s == 0) {
	    $r = $l*255;
		$g = $l*255;
		$b = $l*255;
	} else {
	    $vh = $h*6;
	    $vi = floor($vh);
	    $v1 = $l*(1-$s);
	    $v2 = $l*(1-$s*($vh-$vi));
	    $v3 = $l*(1-$s*(1-($vh-$vi)));
		
		switch($vi){
			case 0:  $vR = $l; $vG = $v3; $vB = $v1; break;
			case 1:  $vR = $v2; $vG = $l; $vB = $v1; break;
			case 2:  $vR = $v1; $vG = $l; $vB = $v3; break;
			case 3:  $vR = $v1; $vG = $v2; $vB = $l; break;
			case 4:  $vR = $v3; $vG = $v1; $vB = $l; break;
			default: $vR = $l; $vG = $v1; $vB = $v2; break;
		}
	
	    $r = $vR*255;
	    $g = $vG*255;
	    $b = $vB*255;
	}    
	return array($r,$g,$b);
}
?>