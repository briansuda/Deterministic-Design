<?php
/*
Brian Suda
brian@suda.co.uk

You see all the fancy circular charts which show you who is connected to whom. This is an easy way to generate those connections in a circular chart.

*/

header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';

$labels = array('Name 0','Name 1','Name 2','Name 3','Name 4','Name 5','Name 6','Name 7','Name 8','Name 9');

$radius = 300;

// Setup labels around the circle
for($i=0;$i<count($labels);$i++){
	$A = ((360/count($labels))*$i);
	$point = getCords($A,$radius+10);
	echo '<text style="text-anchor:end;glyph-anchor:centerline;" fill="#'.getColor($labels[$i]).'" transform="rotate('.(90-$A).' '.($point["x"]-10).','.($point["y"]-10).')"><tspan x="'.($point["x"]-10).'"  y="'.($point["y"]-10).'">'.$labels[$i].'</tspan></text>'."\n";
}

// an array of arrays about which index in the label conneted to which other index in the labels array.
$connections = array(
				array(0,1),
				array(0,2),
				array(0,3),
				array(1,0), 
				array(1,2), 
				array(2,0),				
				array(2,1),
				array(2,7),				
				array(3,8),
				array(3,9),
				array(4,3),				
				array(4,6),
				array(4,7),
				array(4,8), 
				array(4,9),
				array(5,2),
				array(7,1),				 
				array(8,1),
				array(8,0), 
				array(8,7),
				array(8,9),
				array(9,8)
);

// loop through the interconnections and draw an arc between labels
for($i=0;$i<count($connections);$i++){
	$p1 = rand(0,count($labels));
	$p2 = rand(0,count($labels));
		
	$p1 = $connections[$i][0];
	$p2 = $connections[$i][1];
		
	$A1 = ((360/count($labels))*$p1);
	$s1 = getCords($A1,$radius);
	$A2 = ((360/count($labels))*$p2);
	$s2 = getCords($A2,$radius);
	
	
	$aa = (($s2["x"]-$s1["x"])*($s2["x"]-$s1["x"]));
	$bb = (($s2["y"]-$s1["y"])*($s2["y"]-$s1["y"]));
	
	$c = $radius-(sqrt($aa + $bb)/2);
	$r1 = getCords($A1,$c);
	$r2 = getCords($A2,$c);
	
	echo '<path opacity="0.5"   
d="M'.$s1["x"].','.$s1["y"].'C'.($r1["x"]+($radius-$c)).','.($r1["y"]+($radius-$c)).','.(($r2["x"]+($radius-$c))).','.($r2["y"]+($radius-$c)).','.($s2["x"]).','.($s2["y"]).'" stroke="#'.getColor($labels[$p1]).'" 
stroke-width="3" fill="none"/>';


}

echo '<circle cx="'.$radius.'" cy="'.$radius.'" r="'.$radius.'" stroke="black" stroke-width="2" fill="none"/>';
echo '</svg>';

function getColor($str){
	// a simple unique color generator, see /contrast/contrast.php for more information
	return substr(md5($str),0,6);
}

// This converts an arc and radius into an x,y position to be plotted for SVG
function getCords($A,$radius){
	$x = $radius-(sin(deg2rad($A))*$radius);
	$y = $radius-(cos(deg2rad($A))*$radius);
	return array('x'=>$x,'y'=>$y);
}

?>
