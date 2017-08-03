<?php
/*
Brian Suda
brian@suda.co.uk

Using data from the 2014 world cup, this to_from chart visualizes the goals scored and goals conceeded for each team in the tournament.

Usage:
php wc2014_tofrom.php > output2014.svg

*/

header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg">
';
$messages = 
array(
	// Finals
	"Germany"        =>array("Argentina","Brazil","Brazil","Brazil","Brazil","Brazil","Brazil","Brazil","France","Algeria","Algeria","United States","Portugal","Portugal","Portugal","Portugal","Ghana","Ghana"),
	"Argentina"      =>array("Belgium","Switzerland","Nigeria","Nigeria","Nigeria","Bosnia and Herzegovina","Bosnia and Herzegovina","Iran",),	
	
	// Semifinals
	"Netherlands"    =>array("Brazil","Brazil","Brazil","Chile","Chile","Mexico","Mexico","Australia","Australia","Australia","Spain","Spain","Spain","Spain","Spain",),
	"Brazil"         =>array("Germany","Netherlands","Colombia","Colombia","Chile","Croatia","Croatia","Croatia","Cameroon","Cameroon","Cameroon","Cameroon",), 
	
	// Quarter Finals
	"Colombia"       =>array("Brazil","Uruguay","Uruguay","Greece","Greece","Greece","Ivory Coast","Ivory Coast","Japan","Japan","Japan","Japan"),
	"France"         =>array("Switzerland","Switzerland","Switzerland","Switzerland","Switzerland","Nigeria","Nigeria","Honduras","Honduras","Honduras"),
	"Belgium"        =>array("Algeria","Algeria","United States","United States","Korea Republic","Russia",),
	"Costa Rica"     =>array("Uruguay","Uruguay","Uruguay","Greece","Italy",), 
	
	// Round of 16
	"Algeria"        =>array("Germany","Belgium","Korea Republic","Korea Republic","Korea Republic","Korea Republic","Russia",),
	"Switzerland"    =>array("France","France","Ecuador","Ecuador","Honduras","Honduras","Honduras",), 
	"Chile"          =>array("Brazil","Australia","Australia","Australia","Spain","Spain",),
	"Uruguay"        =>array("Costa Rica","England","England","Italy",),
	"United States"  =>array("Belgium","Portugal","Portugal","Ghana","Ghana"),
	"Mexico"         =>array("Croatia","Croatia","Croatia","Cameroon"),
	"Nigeria"        =>array("Argentina","Argentina","Bosnia and Herzegovina"),
	"Greece"         =>array("Costa Rica","Ivory Coast","Ivory Coast"),
	
	// Group Stage
	"Croatia"        =>array("Brazil","Mexico","Cameroon","Cameroon","Cameroon","Cameroon"),
	"Australia"      =>array("Netherlands","Netherlands","Chile","Australia","Australia","Australia"),

	"Ivory Coast"    =>array("Colombia","Greece","Japan","Japan"), 
	"Bosnia and Herzegovina" =>array("Argentina","Iran","Iran","Iran"),
	"Portugal"       =>array("United States","United States","Ghana","Ghana"), 
	"Ghana"          =>array("Germany","Germany","United States","Portugal"),

	"England"        =>array("Uruguay","Italy","Italy",),
	"Ecuador"        =>array("Switzerland","Honduras","Honduras"),
	"Korea Republic" =>array("Algeria","Algeria","Russia"),

	"Japan"          =>array("Colombia","Ivory Coast"),
	"Italy"          =>array("England","England"), 
	"Russia"         =>array("Algeria","Korea Republic"), 

	"Cameroon"       =>array("Brazil",),
	"Spain"          =>array("Netherlands",), 
	"Honduras"       =>array("Ecuador"), 
	"Iran"           =>array("Bosnia and Herzegovina"), 
	

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
