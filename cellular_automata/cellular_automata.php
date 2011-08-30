<?php
/*
Brian Suda
brian@suda.co.uk

Cellular Automata
http://en.wikipedia.org/wiki/Cellular_Automata

This will generate SVG output which can easily be imported into popular vector programs or converted to PDF.

There are several rules available, you can uncomment out the one you would like output for, or add your own.

Usage:
php cellular_automata.php > output.svg

*/

// Set the Dimensions of the output
$width = 180;
$rows  = 129;

// See the Cellular Automata with the current time or a string of your choice
$seed = time();
$seed = hexdec(substr(md5('Brian Suda'),0,10));

$matrix = array();
$j = 1;
for($i=0;$i<$width;$i++){
	// get the bit to seed the first row
	$matrix[0][$i] = ($seed/pow(2,$j))%2;	
	// keeps the mod from getting huge
	if($j > 32) { $j = 1; } else { $j++; }
}

// loop through the and run the Celluar Automata
for($i=0;$i<($rows-1);$i++){
	for($j=0;$j<$width;$j++){
		$matrix[($i+1)][$j] = isOn($j,$matrix[$i]);
	}
}

header("Content-Type: image/svg+xml");
echo '<?xml version="1.0" standalone="no"?>'."\n";
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<svg width="100%" height="100%" version="1.1"
xmlns="http://www.w3.org/2000/svg">

<?php

// loop through the matrix and output SVG for squares
for($i=0;$i<$rows;$i++){
	for($j=0;$j<$width;$j++){
		if($matrix[$i][$j] == 1){
			echo '<rect width="10" height="10" x="'.($j*10).'" y="'.($i*10).'" style="fill:rgb(0,0,0)"/>'."\n";
		}
	}
}
?>
</svg>

<?php
function isOn($pos,$row){
	// Get the parent cells and handle wrapping
	if($pos == 0){ 
		$pos1 = $row[count($row)-1];
	} else {
		$pos1 = $row[$pos-1];
	}
	$pos2 = $row[$pos];
	if($pos == (count($row)-1)){ 
		$pos3 = 0;
	} else {
		$pos3 = $row[$pos+1];
	}

	/* 
	
	// RULE 30
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }

	// RULE 110
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }	

	// RULE 45
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 121
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 73 (not that interesting!)
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 107
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 105
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 90
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }
	
	// RULE 22
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }
	
	// RULE 182
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }
	
	// RULE 150
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }

	// RULE 151
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 85
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 165
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 101
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 166
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }

	// RULE 146
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }

	// RULE 169
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	// RULE 183
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 1; }

	*/
	// RULE 86
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 1) { return 0; }
	if($pos1 == 1 && $pos2 == 0 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 1) { return 0; }
	if($pos1 == 0 && $pos2 == 1 && $pos3 == 0) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 1) { return 1; }
	if($pos1 == 0 && $pos2 == 0 && $pos3 == 0) { return 0; }
	
	
}



?>