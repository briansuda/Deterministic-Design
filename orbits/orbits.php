<?php
/*
Brian Suda
brian@suda.co.uk

Plantary Orbits

*/

$orbit1 = 1000;
$orbit2 = 1500;

// Scale everything down to 100 units so things don't get out of hand!
$scaler = 100/$orbit2;

$steps = 300;
$orbit1_distance = 4680;
$orbit2_distance = 2880;

echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"> 
<svg xmlns="http://www.w3.org/2000/svg" version="1.1">';


//echo '<circle cx="0" cy="0" r="'.($orbit1/2).'" stroke="black" stroke-width="2" fill="none"/>';
//echo '<circle cx="0" cy="0" r="'.($orbit2/2).'" stroke="black" stroke-width="2" fill="none"/>';

// Loop through each 10-degree snapping and make a wedged polygon
for($i=0; $i<=$steps; $i++){
  $k = ($orbit1_distance/$steps)*$i;
  $length = ($orbit1 * $scaler)/2;

  $x1 = (sin(deg2rad($k)) * $length);
  $y1 = (cos(deg2rad($k)) * $length);


  //echo '<circle cx="'.$x1.'" cy="'.$y1.'" r="2" stroke="none" fill="red"/>'."\n";


  $k = ($orbit2_distance/$steps)*$i;
  $length = ($orbit2 * $scaler)/2;

  $x2 = (sin(deg2rad($k)) * $length);
  $y2 = (cos(deg2rad($k)) * $length);


  //echo '<circle cx="'.$x1.'" cy="'.$y1.'" r="2" stroke="none" fill="red"/>'."\n";
  //echo '<circle cx="'.$x2.'" cy="'.$y2.'" r="2" stroke="none" fill="blue"/>'."\n";

  echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:rgb(0,0,0);stroke-width:1" />'."\n";
  //echo '<polygon points="100,100 '.$x.','.$y.' '.$x1.','.$y1.'" fill="#'.stepper($k).'"/>'."\n";

}

echo '</svg>';

/*
This function will return a color between a start and stop color equivalent to the number of steps.
In this case, the two colors represent opposites sides of the windrose and each wedge inbetween will be colored appropriately

returns a hex value

*/
function stepper($angle){
  $a = $angle-10;
  if( $a > 170) { $a = 180-($a-180); }
  $a = $a/10;

  // Number of units between biggest and smallest
  $steps = 18;

  // Start color
  $r1 = 255;
  $g1 = 250;
  $b1 = 245;

  // End color
  $r2 = 0;
  $g2 = 76;
  $b2 = 159;

  $steps = ($steps + 1);
  $r_diff = abs($r1-$r2)/$steps;
  $g_diff = abs($g1-$g2)/$steps;
  $b_diff = abs($b1-$b2)/$steps;

  $r1 = $r1 - ($r_diff*$a);  
  $g1 = $g1 - ($g_diff*$a);  
  $b1 = $b1 - ($b_diff*$a);  

  return 
  str_pad(dechex($r1),2,0,STR_PAD_LEFT).
  str_pad(dechex($g1),2,0,STR_PAD_LEFT).
  str_pad(dechex($b1),2,0,STR_PAD_LEFT);

}

?>