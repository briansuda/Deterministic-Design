<?php
/*
Brian Suda
brian@suda.co.uk

Inspired by Lee Byron's http://www.leebyron.com/ Stream Graphs of movies, I made similar code in SVG to output stream graphs. There is a debate about the usefulness of these graphs, but in this allows you to create and explore the output.

Usage:
php stream.php > output.svg

*/

header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1" 
xmlns="http://www.w3.org/2000/svg">
';

$items = 8;
$steps = 30;
$dataPoints = array();
$max = ($items*50);
$points = 20;

// generate some fake data
for($i=0; $i<$points;$i++){
    $temp = array();
    for($j=0;$j<$items;$j++){
		$temp[$j] = rand(0,20);
    }
    $dataPoints[] = $temp;
}

$prev_points = array();
for($i=0;$i<$items;$i++){
  $to = '';
  $from = false;
  $pos  = 0;
  $prev_x = 0;
  $prev_y = ($max/2);
  $prev_y_from = ($max/2);
  

  for($j=0;$j<$points;$j++){
    $total = array_sum($dataPoints[$j]);
    $x = ($j+1)*$steps;
    $y = (($max/2)+($total/2))-$prev_points[$j];
    if(!($from)){
       $from = 'C'.($steps/2).','.($y-$dataPoints[$j][$i]).','.($steps/2).','.($max/2).',0,'.($max/2);
    }
    $to .= 'C'.($prev_x+($steps/2)).','.$prev_y.','.($prev_x+($steps/2)).','.$y.','.$x.','.$y;

    if(($points-1) == $j){
      $from = ' C'.($x+($steps/2)).','.($max/2).','.($x+($steps/2)).','.($y-$dataPoints[$j][$i]).','.$x.','.($y-$dataPoints[$j][$i]).$from;
    } else {
	  $buffer = (((($max/2)+(array_sum($dataPoints[$j+1])/2))-$prev_points[$j+1])-$dataPoints[$j+1][$i]);
      $from = 'C'.($x+($steps/2)).','.$buffer.','.($x+($steps/2)).','.($y-$dataPoints[$j][$i]).','.$x.','.($y-$dataPoints[$j][$i]).$from;
    }
   

    $prev_x = $x;
    $prev_y = $y;
    $prev_y_from = $y-$dataPoints[$j][$i];
    $prev_points[$j] += $dataPoints[$j][$i];
 

  }
  $x = ($points+1)*$steps;
  $y = ($max/2);
  $to .= 'C'.($prev_x+($steps/2)).','.$prev_y.','.($prev_x+($steps/2)).','.$y.','.$x.','.$y;

  echo '<path d="M0,'.($max/2).$to.$from.'" fill="#'.getColor($i).'" stroke="#'.getColor($i).'" stroke-width="1" />'."\n";
}


echo '</svg>';

function getColor($str){
  return substr(md5($str),0,6);
}

?>
