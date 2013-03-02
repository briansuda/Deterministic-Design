<?php
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';

	$f = fopen('PT.txt','r');
	$lines = array();
	$counter = 0;
	while($line = fgetcsv($f,1000,"\t")){
		$lat  = (int)($line[4]*10);
		$long = $line[5];
		$alt  = $line[16]; // altitude

		// make sure it is a clean altitude
		if((int)$alt > 0 && $long < 0){
			// echo $line[1].' - '.$alt."\n";
			if(!is_array($lines[$lat])){
				$lines[$lat] = array();
			}
			$lines[$lat] = array_merge($lines[$lat],array($long => $alt));
		}
	}	
	fclose($f);

	// sort it
	krsort($lines);
	
	$stepper = 10;
	$counter = 0;
	
	foreach($lines as $l=>$v){
		ksort($v);
		$scaler = $stepper*$counter;
		$string = '0,'.($l+$scaler).' ';
		$prevLong = 0;
		$prevAmp = ($l+$scaler);
		foreach($v as $long=>$alt){
			if($long < 0){
				
				if(((($long+25)*100) - $prevLong) > 40){
					$string .= ($prevLong+1).','.($l+$scaler).' ';					
					$string .= ((($long+25)*100)-1).','.($l+$scaler).' ';					
				}
				
				$amp = (log10($alt)*10);
				$string .= (($long+25)*100).','.(($l+$scaler)-$amp).' ';
				$prevLong = (($long+25)*100);	
				$prevAmp = (($l+$scaler)-$amp);
			}
		}

		if((1300 - $prevLong) > 40){
			$string .= ($prevLong+1).','.($l+$scaler).' ';					
		}
		$string .= '1300,'.($l+$scaler).' ';
		
		$counter++;
		echo '<polyline points="'.$string.'" style="fill:none;stroke:#cccccc;stroke-width:1"/>'."\n";
	}

	echo '</svg>';
?>
