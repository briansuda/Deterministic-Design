<?php
/*
Brian Suda
brian@suda.co.uk

This is a simple loop to create the sunflower pattern in SVG. It is based on the golden ratio

Inspired by:
http://www.darrenhall.info/portfolio/frinds-of-sunnybank-park-logo

The equation:
http://en.wikipedia.org/wiki/Sunflower#Mathematical_model_of_floret_arrangement

*/

header('Content-Type: image/svg+xml');
 
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
 
<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';
$hex = '000000';
for ($i=1;$i<=3000;$i++){
  $r = 20*sqrt($i);
  $theta = $i * 137.5;
  $dot = polar2cartesian($r,$theta);
  echo '<circle cx="'.$dot['x'].'" cy="'.$dot['y'].'" r="10" fill="#'.$hex.'" />'."\n";
}

echo '</svg>';

function polar2cartesian($r,$theta){
  $cart = array('x'=>0,'y'=>0);

  $cart['x'] = $r * cos(deg2rad($theta));
  $cart['y'] = $r * sin(deg2rad($theta));

  return $cart;
}


?>
