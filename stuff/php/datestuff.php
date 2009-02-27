<?php
/*
14:41 29.11.2007
*/
$ieri  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
$azi   = date("Y-m-d");
$maine = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
echo $ieri;
echo "\n<BR>";
echo $azi;
echo "\n<BR>";
echo $maine;
?>