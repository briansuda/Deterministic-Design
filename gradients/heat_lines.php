<?php
/*
Brian Suda
brian@suda.co.uk

This is used to generate a bar which will vary in color depending on intensity.

You can encode 2 variables using this chart, time (left to right) and a second value as the color intensity.

By stacking this bars, you can quickly see patterns or trends.

Beware of the colors you choose, there may be issues when it comes to gradients and color vision issues.

Usage:
php heat_lines.php > output.svg

*/


header("Content-Type: image/svg+xml");
echo '<?xml version="1.0" standalone="no"?>'."\n";
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">

<defs>
    
    <?php
    // input values from source
	// random sample data. Replace this with your information
	for($i=0;$i<20;$i++){
		$values[] = rand(0,20);
	}
    
	// A range of RGB values used to represent the lowest to the highest values.
    $rangeColors = array(
		'255,250,231',
		'219,255,185',
		'136,203,120',
		'33,139,0',
		'184,182,10',
		'255,233,23', 
		'255,160,14',
		'255,116,8',
		'255,51,0',
		'209,28,0',
		'153,0,0',
		'117,0,0');

    // get the max value, this will become the darkest color
    $max = max($values);

    // set the min value to base-line zero
    $min = 0;

    // get the range breakdown split value
    $ranges = ceil(($max/count($rangeColors)));
    
    // get the steps for the gradient
    $steps  = 100/(count($values)-1);
    
    // loop through each value to form a small box
    echo '<linearGradient id="item1" x1="0%" y1="0%" x2="100%" y2="0%">'."\n";
    for($i=0;$i<(count($values)-1);$i++){
     // get the current value to get the gradient start and end range value
     $curr = $values[$i];

     // start the gradient range and end it based on values
     echo '<stop offset="'.($i*$steps).'%" style="stop-color:rgb('.$rangeColors[floor($curr/$ranges)].'); stop-opacity:1"/>'."\n";
    }
    echo '<stop offset="100%" style="stop-color:rgb('.$rangeColors[floor($values[count($values)-1]/$ranges)].'); stop-opacity:1"/>'."\n";
    echo '</linearGradient>';
        
    ?>

</defs>


<rect width="630" height="10" x="10" y="10" style="fill:url(#item1)"/>
</svg>