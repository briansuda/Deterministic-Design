<?php
/*
Brian Suda
brian@suda.co.uk

This does not output any SVG, only a list of the RGB color based on a lat,lon pair. As an example, this is a list of the tallest mountain on each continent.

Usage:
php mountains.php

*/

$mts = array(
	"Kilimanjaro (Volcano Kibo: Uhuru Peak)"=>array("37.353333","-3.075833"),
	"Vinson Massif"=>array("-85.617147","-78.525483"),
	"Kosciuszko"=>array( "148.263333","-36.455981"),
	"Carstensz Pyramid (Puncak Jaya)"=>array("137.183333","-4.083333"),
	"Everest (Sagarmatha or Chomolungma)"=>array("86.925278","27.988056"),
	"Elbrus (Minghi-Tau)"=>array("42.439167","43.355"),
	"Mount McKinley (Denali)"=>array("-151.0074","63.0695"),
	"Aconcagua"=>array("-70.015833","-32.655556")
);
foreach($mts as $mt=>$point){
	echo $mt.': '. colorFromGeo($point[1],$point[0])."\n"; // location
}	
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
