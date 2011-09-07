<?php
/*
Brian Suda
brian@suda.co.uk

This is using the GeoNames database of features in Iceland to generate a point for each of the common types. The result is a map which you can easily see the outline of the entire country without knowledge of the actual borders.

You can read more online at:
http://optional.is/required/2010/06/28/geonames-maps/

Usage:
php features.php > map.svg

*/
	echo '<?xml version="1.0" standalone="no"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
	"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1"
	xmlns="http://www.w3.org/2000/svg">';
	$f = fopen('IS.txt','r');
	while($line = fgetcsv($f,1000,"\t")){
		// the 7000 and 3000 are used to help offset the results in relation to the latitude and longtude, this will vary by country
		$lat = (7000-($line[4]*100))*2;
		$long = ($line[5]*100)+3000;
		$color = substr(md5($line[7]),0,6); // feature
		$color = substr(md5($line[6]),0,6); // feature type
		
		if(($line[6] != 'A') && ($line[6] != 'R') && ($line[6] != 'U')){
			echo '<circle r="1" cy="'.$lat.'" cx="'.$long.'" stroke="black" stroke-width="0" fill="#'.$color.'"/>'."\n";						
		}
	}
	fclose($f);
	echo '</svg>';
?>