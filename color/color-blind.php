<?php
/*
Brian Suda
brian@suda.co.uk

Simulates colorblindness

Color.Vision.Simulate : v0.1
-----------------------------
Freely available for non-commercial use by Matthew Wickline and the
Human-Computer Interaction Resource Network ( http://hcirn.com/ ).

"Color-Defective Vision and Computer Graphics Displays" by Gary W. Meyer and Donald P. Greenberg
http://ieeexplore.ieee.org/iel1/38/408/00007759.pdf?arnumber=7759

"Spectral sensitivity of the foveal cone photopigments between 400 and 500 nm" by V.C. Smith, J. Pokorny
http://www.opticsinfobase.org/abstract.cfm?URI=josaa-22-10-2060

"RGB Working Space Information" by Bruce Lindbloom
http://www.brucelindbloom.com/WorkingSpaceInfo.html


*/

// You can pass in a HEX string
$colors = array('688937','18940d','78e926','ac744f','64f607');
$colors = array('ED1C24');


// Loop through each of the HEX strings and apply the different contrast functions
foreach($colors as $input){
	echo $input."\n";
	echo 'Protanope:   '.Protanope($input)."\n";
	echo 'Deuteranope: '.Deuteranope($input)."\n";
	echo 'Tritanope:   '.Tritanope($input)."\n";	
	echo 'Monochrome:  '.Monochrome($input)."\n";	
	echo "\n";
}

function Protanope($hex){
	return make_conversion($hex,array('x'=>0.7465,'y'=>0.2535,'m'=>1.273463,'yint'=>-0.073894));
}

function Deuteranope($hex){
	return make_conversion($hex,array('x'=>1.4,   'y'=>-0.4,  'm'=>0.968437,'yint'=>0.003331));	
}

function Tritanope($hex){
	return make_conversion($hex,array('x'=>0.1748,'y'=>0,     'm'=>0.062921,'yint'=>0.292119));
}

function Monochrome($hex){
	$r = hexdec(substr($hex,0,2));
	$g = hexdec(substr($hex,2,2));
	$b = hexdec(substr($hex,4,2));
	
	$r = ($r * 0.212656 + $g * 0.715158 + $b * 0.072186);	
	
	return str_pad(dechex($r),2,"0",STR_PAD_LEFT).str_pad(dechex($r),2,"0",STR_PAD_LEFT).str_pad(dechex($r),2,"0",STR_PAD_LEFT);
}

function make_conversion($hex,$modulations){
	$r = hexdec(substr($hex,0,2));
	$g = hexdec(substr($hex,2,2));
	$b = hexdec(substr($hex,4,2));

	$amount = 1;
	$confuse_x    = $modulations['x'];
	$confuse_y    = $modulations['y']; 
	$confuse_m    = $modulations['m'];
	$confuse_yint = $modulations['yint'];

	// Convert source color into XYZ color space
	$pow_r = pow($r, 2.2);
	$pow_g = pow($g, 2.2);
	$pow_b = pow($b, 2.2);
	
	$X = $pow_r * 0.412424  + $pow_g * 0.357579 + $pow_b * 0.180464; // RGB->XYZ (sRGB:D65)
	$Y = $pow_r * 0.212656  + $pow_g * 0.715158 + $pow_b * 0.0721856;
	$Z = $pow_r * 0.0193324 + $pow_g * 0.119193 + $pow_b * 0.950444;
	
	// Convert XYZ into xyY Chromacity Coordinates (xy) and Luminance (Y)
	$chroma_x = $X / ($X + $Y + $Z);
	$chroma_y = $Y / ($X + $Y + $Z);
	// Generate the “Confusion Line" between the source color and the Confusion Point
	$m = ($chroma_y - $confuse_y) / ($chroma_x - $confuse_x); // slope of Confusion Line
	$yint = $chroma_y - $chroma_x * $m; // y-intercept of confusion line (x-intercept = 0.0)
	// How far the xy coords deviate from the simulation
	$deviate_x = ($confuse_yint - $yint) / ($m - $confuse_m);
	$deviate_y = ($m * $deviate_x) + $yint;
	// Compute the simulated color’s XYZ coords
	$X = $deviate_x * $Y / $deviate_y;
	$Z = (1.0 - ($deviate_x + $deviate_y)) * $Y / $deviate_y;
	// Neutral grey calculated from luminance (in D65)
	$neutral_X = 0.312713 * $Y / 0.329016; 
	$neutral_Z = 0.358271 * $Y / 0.329016; 
	// Difference between simulated color and neutral grey
	$diff_X = $neutral_X - $X;
	$diff_Z = $neutral_Z - $Z;
	$diff_r = $diff_X * 3.24071 +   $diff_Z * -0.498571; // XYZ->RGB (sRGB:D65)
	$diff_g = $diff_X * -0.969258 + $diff_Z * 0.0415557;
	$diff_b = $diff_X * 0.0556352 + $diff_Z * 1.05707;	
	
	// Convert to RGB color space
	$dr = $X * 3.24071 +   $Y * -1.53726 +  $Z * -0.498571; // XYZ->RGB (sRGB:D65)
	$dg = $X * -0.969258 + $Y * 1.87599 +   $Z * 0.0415557;
	$db = $X * 0.0556352 + $Y * -0.203996 + $Z * 1.05707;
	// Compensate simulated color towards a neutral fit in RGB space
	$fit_r = (($dr < 0.0 ? 0.0 : 1.0) - $dr) / $diff_r;
	$fit_g = (($dg < 0.0 ? 0.0 : 1.0) - $dg) / $diff_g;
	$fit_b = (($db < 0.0 ? 0.0 : 1.0) - $db) / $diff_b;

	$adjust = array();
	if ($fit_r > 1.0 || $fit_r < 0.0){ $adjust[] = 0; } else { $adjust[] = $fit_r; }
	if ($fit_g > 1.0 || $fit_g < 0.0){ $adjust[] = 0; } else { $adjust[] = $fit_g; }
	if ($fit_b > 1.0 || $fit_b < 0.0){ $adjust[] = 0; } else { $adjust[] = $fit_b; }
	
	$adjust = max($adjust);
			
	// Shift proportional to the greatest shift
	$dr = $dr + ($adjust * $diff_r);
	$dg = $dg + ($adjust * $diff_g);
	$db = $db + ($adjust * $diff_b);
	// Apply gamma correction
	$dr = pow($dr, 1.0 / 2.2);
	$dg = pow($dg, 1.0 / 2.2);
	$db = pow($db, 1.0 / 2.2);
	// Anomylize colors
	$R = $sr * (1.0 - $amount) + $dr * $amount; 
	$G = $sg * (1.0 - $amount) + $dg * $amount;
	$B = $sb * (1.0 - $amount) + $db * $amount;
	// Return values
	 
	return str_pad(dechex($R),2,"0",STR_PAD_LEFT).str_pad(dechex($G),2,"0",STR_PAD_LEFT).str_pad(dechex($B),2,"0",STR_PAD_LEFT);
	
}

?>
