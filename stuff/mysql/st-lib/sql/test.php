<?php
if (!isset($_GET['db'])) { die('specificati baza de date!');} else {$db = $_GET['db'];}
$conn = mysql_connect('localhost', 'travel_agency_site', 'REDACTED') or die('Could not connect: ' . mysql_error());
echo 'Conectare ok!<BR>';
mysql_select_db($db) or die('Nu g&#259;sesc baza de date <B>' . $db . '</B>');
echo 'Baza de date: <B>' . $db . '</B>';
mysql_close($conn);
?> 