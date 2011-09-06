<?php
/*
Brian Suda
brian@suda.co.uk

Using data from the 2010 world cup, this to_from chart visualizes the goals scored and goals conceeded for each team in the tournament.

Usage:
php wc_tofrom.php > output.svg

*/

header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg">
';
$messages = 
array(
	"Spain"       =>array("Netherlands","Germany","Paraguay","Chile","Chile","Portugal","Honduras","Honduras"), 
	"Netherlands" =>array("Uruguay","Uruguay","Uruguay","Brazil","Brazil","Japan","Slovakia","Slovakia", "Denmark","Denmark","Cameroon","Cameroon"),
	"Germany"   =>array("Uruguay","Uruguay","Uruguay","Argentina","Argentina","Argentina","Argentina","Ghana","England","England","England","England","Australia","Australia","Australia","Australia"),
	"Uruguay"     =>array("Netherlands","Netherlands","Germany","Germany","Ghana","Mexico","Korea Republic","Korea Republic","South Africa","South Africa","South Africa"),
	"Argentina"      =>array("Mexico","Mexico","Mexico","Korea Republic","Korea Republic","Korea Republic","Korea Republic","Greece","Greece","Nigeria"),	
	"Brazil"      =>array("Netherlands","Chile","Chile","Chile","Ivory Coast","Ivory Coast","Ivory Coast","Korea DPR","Korea DPR"), 
	"Ghana"       =>array("Uruguay","United States","United States","Australia","Serbia"),
	"Paraguay"    =>array("Slovakia","Slovakia","Italy"), 
	"Japan"       =>array("Denmark","Denmark","Denmark","Cameroon"),
	"Chile"       =>array("Spain","Switzerland","Honduras"),
	"Portugal"    =>array("Korea DPR","Korea DPR","Korea DPR","Korea DPR","Korea DPR","Korea DPR","Korea DPR"), 
	"United States"  =>array("Ghana","England","Slovenia","Slovenia","Algeria"),
	"England"        =>array("Germany","United States","Slovenia"),
	"Mexico"      =>array("Argentina","South Africa","France","France"),
	"Korea Republic" =>array("Uruguay","Argentina","Greece","Greece","Nigeria","Nigeria"),
	"Slovakia"    =>array("Netherlands","New Zealand","Italy","Italy","Italy"), 
	"Ivory Coast" =>array("Brazil","Korea DPR","Korea DPR","Korea DPR"), 
	"Slovenia"       =>array("United States","United States"),
	"Switzerland" =>array("Spain"), 
	"South Africa"=>array("Mexico","France","France"),
	"Australia" =>array("Ghana","Serbia","Serbia"),
	"New Zealand" =>array("Slovakia","Italy"), 
	"Serbia"    =>array("Germany","Australia"),
	
	"Denmark"     =>array("Japan","Cameroon","Cameroon"),
	"Greece"         =>array("Nigeria","Nigeria"),
	"Italy"       =>array("Paraguay","Slovakia","Slovakia","New Zealand"), 
	"Nigeria"        =>array("Korea Republic","Korea Republic","Greece"),
	"Algeria"        =>array(),
	"France"      =>array("South Africa"),
	"Honduras"    =>array(), 
	"Cameroon"    =>array("Netherlands","Denmark"),
	"Korea DPR"   =>array("Brazil"), 
);



$multiplier = 1;
$tCount = 0;
$bars   = array();
$toBars  = array();
$bbars   = array();

foreach($messages as $from=>$to){
  $tCount += count($to);
  $bars[$from] = count($to);
  for($i=0;$i<count($to);$i++){
	$posList = getKeyPos($messages,$to[$i]);
    $toBars[$from][$posList]++;
    $bbars[$posList]++;
  }
  
}

// output top bars
$x = 0;
$bbarpos = array();
foreach($bars as $n=>$b){
  echo '<line x1="'.($x*1).'" y1="10" x2="'.(($x*1)+($b*1)).'" y2="10" stroke="#'.getColor($n).'" stroke-width="'.(3*$multiplier).'" fill="none"/>'."\n";
  

  $lineX = $x;
  foreach($toBars[$n] as $k=>$tB){
     $lineX += $tB;
     $offset = (($k)*5);
     for($j=0;$j<$k;$j++){
		$offset += $bbars[$j];
     }

     $xx = $offset+$bbarpos[$k];
     $bbarpos[$k] += $tB;
     echo '<path d="M'.($lineX-($tB/2)).',15C'.($lineX-($tB/2)).',50,'.($xx+($tB/2)).',50,'.($xx+($tB/2)).',95" stroke="#'.getColor($n).'" stroke-width="'.($tB*$multiplier).'" fill="none" opacity="0.5" />'."\n";

  }  
  
  $x += (($b*1)+5);
}

// output lower bars
$x = 0;
$cn = array_keys($messages);
for($i=0;$i<count($bbars);$i++){
    echo '<line x1="'.($x*1).'" y1="100" x2="'.(($x*1)+($bbars[$i]*1)).'" y2="100" stroke="#'.getColor($cn[$i]).'" stroke-width="3" fill="none"/>'."\n";
    $x += $bbars[$i]+5;
}



echo '</svg>';


function getColor($str){
  return substr(md5($str),0,6);
}

function getKeyPos($list,$key){
	$counter = 0;
	foreach(array_keys($list) as $k){
		if($k == $key) return $counter;
		$counter++;
	}
}

?>
