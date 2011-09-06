<?php
/*
Brian Suda
brian@suda.co.uk

This takes an input of a CSV file, one line per reading of the direction (in 10 degress snappings) and a speed. These values will be summed-up and a windrose plot produced

This was inspired by the http://www.visitnordkyn.no/ logo which is a windrose. It shows both wind direction and using temperature changes its color.

Usage:
php windrose.php > output.svg

Reminder, the output needs to be rotates so that North makes sense.

*/

$dirs    = array();
$counter =0;

// Open the files and put the values an array
if (($handle = fopen("windspeeds.csv", "r")) !== FALSE) {
  while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $cleaner = ((int)($line[0]/10)*10);
    $dirs[$cleaner] += $line[1];
    $counter++;
  }
  fclose($handle);
}

// find the longest arm after the values were summed together
$max = 0;
foreach($dirs as $k=>$v){
  $arm = $v/$counter;
  if($arm > $max) { $max = $arm; }
}

// Scale everything down to 100 units so things don't get out of hand!
$scaler = 100/$max;

echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"> 
<svg xmlns="http://www.w3.org/2000/svg" version="1.1">';

$arm_pos = 0;
// Loop through each 10-degree snapping and make a wedged polygon
foreach($dirs as $k=>$v){
  $length = (($v/$counter)*$scaler);
  $x = 100+(sin(deg2rad($k)) * $length);
  $y = 100+(cos(deg2rad($k)) * $length);

  $arm_pos = $k+10;
  if($arm_pos > 360) { $arm_pos = 10; }

  $length = (($dirs[$arm_pos]/$counter)*$scaler);

  $x1 = 100+(sin(deg2rad($arm_pos)) * $length);
  $y1 = 100+(cos(deg2rad($arm_pos)) * $length);

  echo '<polygon points="100,100 '.$x.','.$y.' '.$x1.','.$y1.'" fill="#'.stepper($k).'"/>'."\n";

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
