<?php
/*
Brian Suda
brian@suda.co.uk

This will convert each pixel into a triangle, alternating between up and down to form a stylized image

Usage:
php triangles.php > triangles.svg

*/
header('Content-Type: image/svg+xml');

echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';

$im = imagecreatefromjpeg("example.jpg");
$x = imagesx($im);
$y = imagesy($im);

$stepper = 10;

// Loop through each pixel and make it a dot
for($i=0;$i<$x;$i++){
  for($j=0;$j<$y;$j++){
	$k = imagecolorat($im, $i, $j);
	$rad = ($stepper/2);

    $r = ($k >> 16) & 0xFF;
    $g = ($k >> 8) & 0xFF;
    $b = $k & 0xFF;

    $hex = str_pad(dechex($r),2,'0',STR_PAD_LEFT).str_pad(dechex($g),2,'0',STR_PAD_LEFT).str_pad(dechex($b),2,'0',STR_PAD_LEFT);

	if($i%2 == 0){
		$p1x = (($i/2)*$stepper);
		$p1y = (($j/2)*$stepper);
		$p2x = (($i/2)*$stepper)+($stepper/2);
		$p2y = (($j/2)*$stepper)+($stepper/2);
		$p3x = (($i/2)*$stepper)+$stepper;
		$p3y = (($j/2)*$stepper);
		echo '<polygon points="'.$p1x.','.$p1y.' '.$p2x.','.$p2y.' '.$p3x.','.$p3y.'" fill="#'.$hex.'"/>';
	} elseif($i%2 == 1) {
		$p1x = (($i/2)*$stepper);
		$p1y = (($j/2)*$stepper);
		$p2x = (($i/2)*$stepper)+($stepper/2);
		$p2y = (($j/2)*$stepper)-($stepper/2);
		$p3x = (($i/2)*$stepper)+$stepper;
		$p3y = (($j/2)*$stepper);
		echo '<polygon points="'.$p1x.','.$p1y.' '.$p2x.','.$p2y.' '.$p3x.','.$p3y.'" fill="#'.$hex.'"/>';
	}
	
    echo "\n";
    
  }
}

echo '</svg>';
?>
