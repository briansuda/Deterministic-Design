<?php
/*
Brian Suda
brian@suda.co.uk

This code is used to randomly place shapes inside of a polygon with no overlap


Usage:
php pnp.php > output.svg

*/

// Rough bounding box of any polygon shape. Point Order matters!
// This is a generic star
$boundingShape = array(
	array(500,17),
	array(645,312),
	array(970,360),
	array(735,589),
	array(790,913),
	array(500,760),
	array(208,913),
	array(264,589),
	array(28,360),
	array(354,312)
);

$fillerObjects = array( );

header('Content-Type: image/svg+xml');
 
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
 
<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';
for ($i=0;$i<10000;$i++){
	// Randomly placed objects
	$x = rand(0,1000);
	$y = rand(0,1000);
	//$scale=rand(5,25);
	//$scale=$scale*$scale;
	$scale=rand(50,200);
	//$scale=100;
	$thing = rand(0,1);
	$object = bag_of_objects($thing,$x,$y,$scale/100,true);

	// For dubgging large loops!
	file_put_contents('php://stderr', $i."\n");
		
	// Check to see if it fits inside the bounding shape!
	if (inBoundingShape($object,$boundingShape)){
		// Check to see if it intersects with any previously placed object inside the boundingShape
		$counter = 0;
		for($j=0;$j<count($fillerObjects);$j++){
			if(fillerCollision($object,$fillerObjects[$j])){
				//echo 'Overlap and drop'."\n";			
				$counter++;
				break;
			}

			for($k=0;$k<count($object);$k++){
				if(point_in_polygon($fillerObjects[$j], $object[$k])){
					$counter++;
				}
				if($counter>0){
					break;
				}	
			}
			if($counter==0){
				for($k=0;$k<count($fillerObjects[$j]);$k++){
					if(point_in_polygon($object, $fillerObjects[$j][$k])){
						$counter++;
					}
					if($counter>0){
						break;
					}
				}

			}
		}
		// Check that there is no overlap or collisions
		if ($counter == 0){
			// output the object and then put it into the list to check for future collisions
			echo bag_of_objects($thing,$x,$y,$scale/100);
			$fillerObjects[] = $object;		
		}
	}
}

echo '</svg>';

function point_in_polygon($boundingShape, $point){
  $verticies = count($boundingShape);
  $c = false;
  $testx = $point[0];
  $testy = $point[1];
  $j = $verticies-1;

  for ($i = 0; $i < $verticies; $j = $i++) {
    if ( (($boundingShape[$i][1]>$testy) != ($boundingShape[$j][1]>$testy)) &&
	 ($testx < ($boundingShape[$j][0]-$boundingShape[$i][0]) * ($testy-$boundingShape[$i][1]) / ($boundingShape[$j][1]-$boundingShape[$i][1]) + $boundingShape[$i][0]) ){
    	$c = !$c;
		//echo '<!-- C: '.$c." -->\n";
	}
	
  }
  return $c;
}

function fillerCollision($object,$fillerObject){
	for($i=0;$i<(count($object));$i++){
		$x1 = $object[$i][0];
		$y1 = $object[$i][1];
		$j = $i+1;
		if ($j >= count($object)) { $j = 0;  }
		$x2 = $object[($j)][0];
		$y2 = $object[($j)][1];
		if(($x2-$x1) == 0){
			$m1 = 0.0000001;
		} else {
			$m1 = (($y2-$y1)/($x2-$x1));			
		}
		$b1 = $y1-($m1*$x1);
		
		for($l=0;$l<(count($fillerObject));$l++){
			$x1_2 = $fillerObject[$l][0];
			$y1_2 = $fillerObject[$l][1];
			$k = $l+1;
			if ($k >= count($fillerObject)) { $k = 0;  }
			$x2_2 = $fillerObject[($k)][0];
			$y2_2 = $fillerObject[($k)][1];
			
			if(($x2_2-$x1_2) == 0){
				$m2 = 0.0000000001;
			} else {
				$m2 = (($y2_2-$y1_2)/($x2_2-$x1_2));
			}
			$b2 = $y1_2-($m2*$x1_2);
			
			if($m1-$m2 != 0){
				$x=($b2-$b1)/($m1-$m2);		
				$x_lesser = $x1;
				$x_greater = $x2;
				if($x2<$x_lesser){ $x_lesser = $x2; $x_greater = $x1; }

				$x_2_lesser = $x1_2;
				$x_2_greater = $x2_2;
				if($x2_2<$x_2_lesser){ $x_2_lesser = $x2_2; $x_2_greater = $x1_2; }

				if(($x > $x_lesser) && ($x < $x_greater) && ($x > $x_2_lesser) && ($x < $x_2_greater)){ return true; }				
			}
		}
	}
	
	return false;
}

function inBoundingShape($object,$boundingShape){
	$counter = 0;
	for($k=0;$k<count($object);$k++){
		if (@point_in_polygon($boundingShape,$object[$k])){
			$counter++;
		}
	}
	
	// If all the points in the shape are in the polygon, then we're good to go!
	if ($counter == count($object)){
		return true;
	} else {
		return false;
	}
}

function bag_of_objects($index=0,$x,$y,$scale,$boundingBox=false){
	$thing = '';
	switch($index){
		// Rectangle
		default: case 0: 
			// rough bounding box to prevent any overlap
			if($boundingBox){
				$thing = array(array(0*$scale+$x,0*$scale+$y),array(10*$scale+$x,0*$scale+$y),array(10*$scale+$x,10*$scale+$y),array(0*$scale+$x,10*$scale+$y));
			} else {
				// actual SVG shape, this could be anything
				$thing = '<g transform="translate('.$x.','.$y.') scale('.$scale.','.$scale.')">
				<rect x="1" y="1" width="8" height="8"/>
				</g>';
			}
		break;
		// Circle
		case 1: 
			if($boundingBox){
				$thing = array(array(3*$scale+$x,0*$scale+$y),array(7*$scale+$x,0*$scale+$y),array(10*$scale+$x,3*$scale+$y),array(10*$scale+$x,7*$scale+$y),array(7*$scale+$x,10*$scale+$y),array(3*$scale+$x,10*$scale+$y),array(0*$scale+$x,7*$scale+$y),array(0*$scale+$x,3*$scale+$y));
			} else {
				$thing = '<g transform="translate('.$x.','.$y.') scale('.$scale.')">
							<circle fill="#FFFFFF" stroke="#000000" stroke-miterlimit="10" cx="5" cy="5" r="3.726"/>
						</g>';
			}
		break;
	}
	
	return $thing;
}

?>
