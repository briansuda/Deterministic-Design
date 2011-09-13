<?php
/*
Brian Suda
brian@suda.co.uk

This loops through the image and outputs a random shape for each pixel in the image. Avoid using large images due to performance reasons.

The idea comes from the T-Platforms logo.
http://www.behance.net/gallery/T-Platforms/2077724

Usage:
php shapes.php > shapes.svg

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

	switch(rand(1,4)){
		case 1:
			echo '<circle cx="'.($i*$stepper).'" cy="'.($j*$stepper).'" r="'.$rad.'" fill="#'.$hex.'" />';
			break;
		case 2:
			echo '<rect x="'.(($i*$stepper)-$rad).'" y="'.(($j*$stepper)-$rad).'" width="'.($rad*2).'" height="'.($rad*2).'" fill="#'.$hex.'" />';
			break;
		case 3:
			echo '<circle cx="'.($i*$stepper).'" cy="'.($j*$stepper).'" r="'.$rad.'" fill="#'.$hex.'" />';
			switch(rand(1,4)){
				case 1:
				echo '<rect x="'.(($i*$stepper)-$rad).'" y="'.(($j*$stepper)-$rad).'" width="'.($rad*2).'" height="'.$rad.'" fill="#'.$hex.'" />';
				break;
				case 2:
				echo '<rect x="'.(($i*$stepper)-$rad).'" y="'.(($j*$stepper)-$rad).'" width="'.$rad.'" height="'.($rad*2).'" fill="#'.$hex.'" />';
				break;
				case 3:
				echo '<rect x="'.(($i*$stepper)-$rad).'" y="'.($j*$stepper).'" width="'.($rad*2).'" height="'.$rad.'" fill="#'.$hex.'" />';
				break;
				case 4:
				echo '<rect x="'.($i*$stepper).'" y="'.(($j*$stepper)-$rad).'" width="'.$rad.'" height="'.($rad*2).'" fill="#'.$hex.'" />';
				break;
			}
			break;
		case 4:
			switch(rand(1,4)){
				case 1:
				echo '<path d="M'.(($i*$stepper)-$rad).' '.(($j*$stepper)-$rad).' L'.(($i*$stepper)-$rad).' '.(($j*$stepper)+($rad)).' L'.(($i*$stepper)+($rad)).' '.(($j*$stepper)+($rad)).' Z"  fill="#'.$hex.'" />';
				break;
				case 2:
				echo '<path d="M'.(($i*$stepper)-$rad).' '.(($j*$stepper)-$rad).' L'.(($i*$stepper)+$rad).' '.(($j*$stepper)-($rad)).' L'.(($i*$stepper)+($rad)).' '.(($j*$stepper)+($rad)).' Z"  fill="#'.$hex.'" />';
				break;
				case 3:
				echo '<path d="M'.(($i*$stepper)-$rad).' '.(($j*$stepper)+$rad).' L'.(($i*$stepper)+$rad).' '.(($j*$stepper)+($rad)).' L'.(($i*$stepper)-($rad)).' '.(($j*$stepper)+($rad)).' Z"  fill="#'.$hex.'" />';
				break;
				case 4:
				echo '<path d="M'.(($i*$stepper)-$rad).' '.(($j*$stepper)-$rad).' L'.(($i*$stepper)-$rad).' '.(($j*$stepper)+($rad)).' L'.(($i*$stepper)+($rad)).' '.(($j*$stepper)-($rad)).' Z"  fill="#'.$hex.'" />';
				
				break;
				
			}
			break;
	}
    echo "\n";
    
  }
}


echo '</svg>';

function rgb2lum($rgb){
	$rgb[0] = $rgb[0]/255;
	$rgb[1] = $rgb[1]/255;
	$rgb[2] = $rgb[2]/255;

    $max = max($rgb);
    $min = min($rgb);

	// Luminosity
    return ($max + $min) / 2;

}

?>
