<?php
/*
Brian Suda
brian@suda.co.uk

This is based on several different functions to compute maximum color contrast to either white or black as the complimentary color. This is ideal for Screen where there the light is emitted rather than reflected as in print.

See Also:
http://24ways.org/2010/calculating-color-contrast

TODO:
Find a good contrast function for CMYK print. 

*/

// You can pass in a HEX string
$words = array('688937','18940d','78e926','ac744f','64f607');

// Or use the getColor function to convert a string into a HEX color
$words = array(getColor('02010'),getColor('02019'),getColor('02020'));


// Loop through each of the HEX strings and apply the different contrast functions
foreach($words as $input){
	echo $input."\n";
	echo '50%: '.contrast(($input))."\n";
	echo 'YIQ: '.yiq_contrast(($input))."\n";
	echo '  L: '.l_contrast(($input))."\n";	
	echo "\n";
}

// This is a very simple algorythm for consistantly returning a color for a string.
// Hat Tip to Dopplr.com where I first saw it used to generate a color for every city
function getColor($input){
	return substr(md5($input),0,6);
}

// Very simple, naive function. It is overly weighted to Red.
function contrast($hexcolor){
    return (hexdec($hexcolor) > 0xffffff/2)?'black':'white';
}


/* http://en.wikipedia.org/wiki/Luma_(video) */
function l_contrast($hexcolor){
	$r = hexdec(substr($hexcolor,0,2));
	$g = hexdec(substr($hexcolor,2,2));
	$b = hexdec(substr($hexcolor,4,2));	
	
	$l = (0.2126 *$r) + (0.7152 * $g) + (0.0722 * $b);

	if($l >= 128) { return 'black'; } else { return 'white'; }
	
}

/* http://en.wikipedia.org/wiki/YIQ */
function yiq_contrast($hexcolor){
	$r1 = hexdec(substr($hexcolor,0,2));
	$g1 = hexdec(substr($hexcolor,2,2));
	$b1 = hexdec(substr($hexcolor,4,2));

	$yiq = (($r1*299)+($g1*587)+($b1*114))/1000;

	if($yiq >= 128){ return 'black'; } else { return 'white'; }

}
?>
