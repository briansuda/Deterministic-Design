<?php
/*
Brian Suda
brian@suda.co.uk

This will attempt to convert the imaged into only a few colors and make a stylized image where the dots blob together like liquid

Usage:
php dots-bleed.php > bleed.svg

*/
header('Content-Type: image/svg+xml');

echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';


$im = imagecreatefromjpeg("map.jpg");
$x = imagesx($im);
$y = imagesy($im);

$stepper = 10;

// Loop through each pixel and make it a dot
for($i=0;$i<$x;$i++){
  for($j=0;$j<$y;$j++){
	$k = imagecolorat($im, $i, $j);
	$k = imagecolorsforindex($im, $k);
	$rad = ($stepper/2);

	$k = closestColor($k);

	$hex = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	$aboveLeft  = -1;
	$aboveRight = -1;
	$belowLeft  = -1;
	$belowRight = -1;
	$top        = -1;
	$bottom     = -1;
	$left       = -1;
	$right      = -1;
    
	if (($i-1) >= 0)  { 
		$k = imagecolorat($im, $i-1, $j); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$left = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
	if (($i+1) < $x)  { 
		$k = imagecolorat($im, $i+1, $j); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$right = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
	if (($j-1) >= 0)  { 
		$k = imagecolorat($im, $i, $j-1); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$top = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
	if (($j+1) < $y)  { 
		$k = imagecolorat($im, $i, $j+1); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$bottom = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
	if ((($i-1) >= 0) && (($j-1) >= 0))  { 
		$k = imagecolorat($im, $i-1, $j-1); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$aboveLeft = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
	if ((($i+1) < $x) && (($j-1) >= 0)) { 
		$k = imagecolorat($im, $i+1, $j-1); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$aboveRight = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
    
	if ((($i-1) >= 0) && (($j+1) < $y)) { 
		$k = imagecolorat($im, $i-1, $j+1); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
		$belowLeft = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
	if ((($i+1) < $x) && (($j+1) < $y)) { 
		$k = imagecolorat($im, $i+1, $j+1); 
		$k = imagecolorsforindex($im, $k);
		$k = closestColor($k);
	   	$belowRight = str_pad(dechex($k['red']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['green']),2,'0',STR_PAD_LEFT).str_pad(dechex($k['blue']),2,'0',STR_PAD_LEFT);
	}
    
	// Top Left
	if (($hex == $aboveLeft) || ($hex == $top) || ($hex == $left)) {
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";			
	} elseif ($top==$left){
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$top.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";		
	} elseif ((colorLess($hex,$top) < 1) && ($top != -1)){
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$top.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	} elseif ($top != -1)  {
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	}
	
	// Bottom Left	
	if(($hex == $belowLeft) || ($hex == $bottom) || ($hex == $left)){
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";			
	} elseif ($bottom==$left && $bottom != -1){
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)).'" fill="#'.$bottom.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";		
	} elseif ((colorLess($hex,$bottom) < 1) && ($bottom != -1)) {
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)).'" fill="#'.$bottom.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	} elseif ($bottom != -1) {
		echo '<rect x="'.(($i*$stepper)-($stepper/2)).'" y="'.(($j*$stepper)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	}
    
	// Top Right
	if (($hex == $aboveRight) || ($hex == $top) || ($hex == $right)){
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";			
	} elseif ($top==$right){
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$top.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";		
	} elseif ((colorLess($hex,$top) < 1) && ($top != -1)){
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$top.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";			
	} elseif ($top != -1) {
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)-($stepper/2)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	}
	// Bottom Right
	if(($hex == $belowRight) || ($hex == $bottom) || ($hex == $right)){
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";			
	} elseif ($bottom==$right && $bottom != -1){
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)).'" fill="#'.$bottom.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";		
	} elseif ((colorLess($hex,$bottom) < 1) && ($bottom != -1)) {
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)).'" fill="#'.$bottom.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	} elseif ($bottom != -1) {
		echo '<rect x="'.(($i*$stepper)).'" y="'.(($j*$stepper)).'" fill="#'.$hex.'" width="'.$rad.'" height="'.$rad.'"/>'."\n";
	}
	
	echo '<circle cx="'.($i*$stepper).'" cy="'.($j*$stepper).'" r="'.$rad.'" fill="#'.$hex.'" />';
	echo "\n";		
	
    
  }
}


echo '</svg>';

function closestColor($rgb){
	$r = $rgb['red'];
    $g = $rgb['green'];
    $b = $rgb['blue'];
    
	$smallest = 765;
	$new_color = array('red'=>0,'green'=>0,'blue'=>0);
	$colors = array(
		array('red'=>255,'green'=>255,'blue'=>255),
		array('red'=>0,'green'=>0,'blue'=>0),
		array('red'=>160,'green'=>150,'blue'=>140),
		array('red'=>240,'green'=>220,'blue'=>220),
		array('red'=>180,'green'=>175,'blue'=>175),		
		array('red'=>170,'green'=>150,'blue'=>150),
		array('red'=>125,'green'=>90,'blue'=>90),
	);

	foreach($colors as $c){
		$tSmall = colorDistance($r,$g,$b,$c['red'],$c['green'],$c['blue']);

		if($smallest > $tSmall){
			$new_color['red']   = $c['red'];
			$new_color['green'] = $c['green'];
			$new_color['blue']  = $c['blue'];

			$smallest = $tSmall;
		}
		
	}
	
	
	return $new_color;
}
function colorDistance($r,$g,$b,$target_r,$target_g,$target_b){
	$nR = abs($r-$target_r);
	$nG = abs($g-$target_g);
	$nB = abs($b-$target_b);
	
	return $nR+$nG+$nB;
}

function colorLess($rgb1,$rgb2){	
	$r = hexdec(substr($rgb1,0,2));
	$g = hexdec(substr($rgb1,2,2));
	$b = hexdec(substr($rgb1,4,2));

	$target_r = hexdec(substr($rgb2,0,2));
	$target_g = hexdec(substr($rgb2,2,2));
	$target_b = hexdec(substr($rgb2,4,2));

	$counter = 0;
	if ($r > $target_r) { $counter++; } else { $counter--; }
	if ($g > $target_g) { $counter++; } else { $counter--; }
	if ($b > $target_b) { $counter++; } else { $counter--; }

	return $counter;
}

?>
