<?php
/*
Brian Suda
brian@suda.co.uk

A set of functions to convert an inputted color into the color which can be made distinct for color deficencies

Based on:
Color.Vision.Daltonize : v0.1
------------------------------
"Analysis of Color Blindness" by Onur Fidaner, Poliang Lin and Nevran Ozguven.
http://scien.stanford.edu/class/psych221/projects/05/ofidaner/project_report.pdf
	
"Digital Video Colourmaps for Checking the Legibility of Displays by Dichromats" by Françoise Viénot, Hans Brettel and John D. Mollon
http://vision.psychol.cam.ac.uk/jdmollon/papers/colourmaps.pdf

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
	echo "\n";
}

function Protanope($hex){
	return make_conversion($hex,array(0,2.02344,-2.52581,
									  0,1,0,
									  0,0,1));
}

function Deuteranope($hex){
	return make_conversion($hex,array(1,0,0,
									  0.494207,0,1.24827,
									  0,0,1));	
}

function Tritanope($hex){
	return make_conversion($hex,array(1,0,0,
									  0,1,0,
									  -0.395913,0.801109,0.0));
}

function make_conversion($hex,$modulations){
	$r = hexdec(substr($hex,0,2));
	$g = hexdec(substr($hex,2,2));
	$b = hexdec(substr($hex,4,2));
	
	print 'r: '.$r."\n";
	
	// RGB to LMS matrix conversion
	$L = (17.8824 *$r)+(43.5161 *$g)+(4.11935*$b);
	$M = (3.45565 *$r)+(27.1554 *$g)+(3.86714*$b);
	$S = (.0299566*$r)+(0.184309*$g)+(1.46709*$b);


	print 'L: '.$L."\n";
	
	// Simulate color blindness
	$l = ($modulations[0]*$L)+($modulations[1]*$M)+($modulations[2]*$S);
	$m = ($modulations[3]*$L)+($modulations[4]*$M)+($modulations[5]*$S);
	$s = ($modulations[6]*$L)+($modulations[7]*$M)+($modulations[8]*$S);	


	print 'l: '.$l."\n";
	
	// LMS to RGB matrix conversion
	$R = (0.0809444479   *$l)+(-0.130504409  *$m)+(0.116721066 *$s);
	$G = (-0.0102485335  *$l)+(0.0540193266  *$m)+(-0.113614708*$s);
	$B = (-0.000365296938*$l)+(-0.00412161469*$m)+(0.693511405 *$s);
	
	print 'R: '.$R."\n";
	
	// Isolate invisible colors to color vision deficiency (calculate error matrix)
	$R = $r-$R;
	$G = $g-$G;
	$B = $b-$B;	
	
	print 'R: '.$R."\n";
	
	
	// Shift colors towards visible spectrum (apply error modifications)
	$RR = ( 0*$R)+(0*$G)+(0*$B);
	$GG = (.7*$R)+(1*$G)+(0*$B);
	$BB = (.7*$R)+(0*$G)+(1*$B);

	print 'RR:'.$RR."\n";

	// Add compensation to original values	
	$R = $RR+$r;
	$G = $GG+$g;
	$B = $BB+$b;

	print 'R: '.$R."\n";
	
	// Clamp values
	if($R < 0)   $R=0;
	if($R > 255) $R=255;
	if($G < 0)   $G=0;
	if($G > 255) $G=255;
	if($B < 0)   $B=0;
	if($B > 255) $B=255;	
	 
	return str_pad(dechex($R),2,"0",STR_PAD_LEFT).str_pad(dechex($G),2,"0",STR_PAD_LEFT).str_pad(dechex($B),2,"0",STR_PAD_LEFT);
	
}

?>
