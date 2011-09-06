<?php
/*
Brian Suda
brian@suda.co.uk

This will make a box of a set height and width with a maximum radius on each randomly sized and placed dot. Each dot added will need to not overlap with any other.

Warning:
This will take a long time to run, it is looping through alot of dots in memory looking for collisions, so be patient

Usage:
php pokadots.php > output.svg

*/
$width  = 1000;
$height = 1000;
$radius = 10;

$list = array();
for($i=0;$i<50000;$i++){
	// make a random circle object
	$circle = array();
	$circle['r'] = (rand(2,$radius)+1);
	$circle['x'] = rand(0,$width);
	$circle['y'] = rand(0,$height);

	// check bounds
	if (($circle['x']+$circle['r']) > $width){  $circle['x'] = $width -$circle['r']; }
	if (($circle['y']+$circle['r']) > $height){ $circle['y'] = $height-$circle['r']; }

	if (($circle['x']-$circle['r']) < 0){ $circle['x'] = $circle['r']; }
	if (($circle['y']-$circle['r']) < 0){ $circle['y'] = $circle['r']; }
		
	// see if it overlaps with any circle in the list
	if(!(overlap($circle,$list))){
		$list[] = $circle;
	}
}

function overlap($needle,$haystack){		
	foreach($haystack as $h){
		// pathogorus theorum
		$a = abs($h['x'] - $needle['x']);
		$b = abs($h['y'] - $needle['y']);
		$c = ($a*$a) + ($b*$b);
		$c = sqrt($c);
				
		// see if the hypotenus is shorter than the radi. Must be overlapping!
		if ($c < ($h['r']+$needle['r'])){ return true; }
	}
	
	return false;
}

function getColor($str){
	return substr(md5($str),0,6);
}

header('Content-Type: image/svg+xml');

echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';

// output the circles in SVG
foreach($list as $c){
	// here you can use the getColor algorithm or fix it to a single HEX value
    echo '<circle cx="'.$c['x'].'" cy="'.$c['y'].'" r="'.$c['r'].'" fill="#'.getColor($c['x'].$c['y']).'" style="opacity: 0.75" />';	
}
echo '</svg>';

?>