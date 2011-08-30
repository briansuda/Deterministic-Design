<?php
/*
Brian Suda
brian@suda.co.uk

MIT Media Lab created a series of logos for their staff. They were not deterministically designed. I thought it would have been nicer to use parts of the Staff members' name, position, department, etc. to determine the colors and angles.

This is a basic demo of how to replicate the MIT Media Lab Logo based on some textual input.

*/
$str = md5('Welcome, The Entire Land');

$color1 = substr($str,0,6 );
$color2 = substr($str,6,6 );
$color3 = substr($str,12,6);

$x1 = substr($str,18,2);
$y1 = substr($str,20,2);

$x2 = substr($str,22,2);
$y2 = substr($str,24,2);

$x3 = substr($str,26,2);
$y3 = substr($str,28,2);

$extra = substr($str,30,2);

// make the MIT logo
echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="265px" height="265px" viewBox="0 0 265 265">';


echo '<linearGradient id="color1" gradientUnits="userSpaceOnUse" x1="0" y1="'.(hexdec($y1)+10).'" x2="'.(hexdec($x1)+10).'" y2="'.(hexdec($y1)+10).'"><stop offset="0" style="stop-color:#'.$color1.'"/><stop offset="1" style="stop-color:#FFFFFF;stop-opacity:10"/></linearGradient>';
echo '<polygon fill="url(#color1)" stroke="none" points="0,0 0,50 '.hexdec($x1).','.(hexdec($y1)+10).' '.(hexdec($x1)+10).','.(hexdec($y1)+10).' '.(hexdec($x1)+10).','.hexdec($y1).' 50,0"/>';


echo '<linearGradient id="color2" gradientUnits="userSpaceOnUse" x1="0" y1="'.(hexdec($y2)+10).'" x2="'.(hexdec($x2)+10).'" y2="'.(hexdec($y2)+10).'"><stop offset="0" style="stop-color:#'.$color2.'"/><stop offset="1" style="stop-color:#FFFFFF;stop-opacity:10"/></linearGradient>';
echo '<polygon fill="url(#color2)" stroke="none" points="0,265 0,215 '.hexdec($x2).','.hexdec($y2).' '.(hexdec($x2)+10).','.hexdec($y2).' '.(hexdec($x2)+10).','.(hexdec($y2)+10).' 50,265"/>';


echo '<linearGradient id="color3" gradientUnits="userSpaceOnUse" x1="'.hexdec($x3).'" y1="0" x2="'.(hexdec($x3)+10).'" y2="'.(hexdec($y3)+10).'"><stop offset="0" style="stop-color:#'.$color3.'"/><stop offset="1" style="stop-color:#FFFFFF;stop-opacity:10"/></linearGradient>';
echo '<polygon fill="url(#color3)" stroke="none" points="215,0 265,0 265,50 '.(hexdec($x3)+10).','.(hexdec($y3)+10).' '.hexdec($x3).','.(hexdec($y3)+10).' '.hexdec($x3).','.hexdec($y3).'"/>';

echo '<rect x="'.hexdec($x1).'" y="'.hexdec($y1).'" stroke="#000000" fill="#000000" stroke-miterlimit="1" width="10" height="10"/>';
echo '<rect x="'.hexdec($x2).'" y="'.hexdec($y2).'" stroke="#000000" fill="#000000" stroke-miterlimit="1" width="10" height="10"/>';
echo '<rect x="'.hexdec($x3).'" y="'.hexdec($y3).'" stroke="#000000" fill="#000000" stroke-miterlimit="1" width="10" height="10"/>';

echo '</svg>';

?>