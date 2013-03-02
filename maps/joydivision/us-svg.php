<?php
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">';

	$f = fopen('US.txt','r');
	$lines = array();
	$counter = 0;
	while($line = fgetcsv($f,1000,"\t")){
		$lat  = (int)($line[4]*10);
		$long = $line[5];
		$alt  = $line[16]; // altitude
		
		
		
		// make sure it is a clean altitude
		if((int)$alt > 0){
			// echo $line[1].' - '.$alt."\n";
			if(!is_array($lines[$lat])){
				$lines[$lat] = array();
			}

			$lines[$lat] = $lines[$lat] + array($long => $alt);				
						
		}
	}	
	fclose($f);

	// sort it
	krsort($lines);
	$keys = array_keys($lines);
	sort($keys);
	$min = $keys[0];
	$max = $keys[(count($keys)-1)];
	
	$stepper = 10;
	$counter = 0;
	
	
	for($l=$max;$l>=$min;$l--){
		$v = $lines[$l];
		
		$scaler = $stepper*$counter;
		$string = '0,'.($l+$scaler).' ';

		if(is_array($v)){
			ksort($v);

			$prevLong = 0;
			$prevAmp = ($l+$scaler);

			foreach($v as $long=>$alt){
				if(strlen($long) != 1){

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

			if((3300 - $prevLong) > 40){
				$string .= ($prevLong+1).','.($l+$scaler).' ';					
			}
		}
		
		$string .= '3300,'.($l+$scaler).' ';

		$counter++;
		echo '<!-- '.$l.' -->';
		echo '<polyline points="'.$string.'" style="fill:black;stroke:#cccccc;stroke-width:1"/>'."\n";
		
		
	}

	echo '</svg>';
?>