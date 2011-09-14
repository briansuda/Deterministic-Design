<?php
/*
Brian Suda
brian@suda.co.uk

Bump Charts

Given a bunch of objects and their relative placement in a list over time, we can plot their rise and fall. Ideal for stocks each day/month/week or sports teams over the years, etc.

See Also:
http://junkcharts.typepad.com/junk_charts/bumps_chart/

Usage:
php bump.php > output.svg

*/

$scaler = 30; // vertical spacing
$length = 60; // length of the line for the data point
$slug   = 30; // Space between data points

// One array for each object which contains their position ranking
$data = array(
  array(1,2,3,4,5,6,6,6,5,4,3,4,5),
  array(2,1,1,1,1,1,1,2,2,1,2,3,2),
  array(3,5,2,2,2,2,3,1,1,2,1,2,1),
  array(4,4,4,3,4,4,2,3,3,3,4,1,3),
  array(5,3,5,5,3,3,5,5,6,5,6,6,6),
  array(6,6,6,6,6,5,4,4,4,6,5,5,4)
);

echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';

echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1">';
for($i=0;$i<count($data);$i++){
 for($j=0;$j<(count($data[$i])-1);$j++){
   $next_point = $data[$i][$j+1];
   // Need to make the lines clear
   echo '<line 
	  x1="'.(($j*$length)+($j*$slug)).'" 
	  y1="'.(($data[$i][$j])*$scaler).'" 
 	  x2="'.((($j+1)*$length)+($j*$slug)).'" 
	  y2="'.(($data[$i][$j+1])*$scaler).'" 
	  style="stroke:#'.substr(md5($i),0,6).';stroke-width:2" 
	/>'."\n";

   if(($j+2) != count($data[$i])){
   // spacer to give some air between the data points 
   echo '<line 
	x1="'.((($j+1)*$length)+($j*$slug)).'"
	y1="'.(($data[$i][$j+1])*$scaler).'"
	x2="'.((($j+1)*$length)+(($j+1)*$slug)).'"
	y2="'.(($data[$i][$j+1])*$scaler).'"
	style="stroke:rgb(200,200,200);stroke-width:1"
      />';
  // Create vertical lines
  echo '<line
	x1="'.((($j+1)*$length)+($j*$slug)).'"
	y1="0"
	x2="'.((($j+1)*$length)+($j*$slug)).'"
	y2="'.((count($data)*$scaler)+$scaler).'"
	style="stroke:rgb(220,220,220);stroke-width:1"
	 />';
  echo '<line
	x1="'.((($j+1)*$length)+(($j+1)*$slug)).'"
	y1="0"
	x2="'.((($j+1)*$length)+(($j+1)*$slug)).'"
	y2="'.((count($data)*$scaler)+$scaler).'"
	style="stroke:rgb(220,220,220);stroke-width:1"
	 />';
  }

 }
}
echo '</svg>';

?>
