<?php
/*
ubmypg.php
started 2007-11-02
02:00 2007-11-17
*/
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// SCRIPT INFO
$prog_file = "ubmypg.php";
$prog_name = "Ub MyPG";
$prog_desc = "MySQL / PostgreSQL Query Tool";
$prog_version = "1.0.071116";
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// EDIT HERE
define("_UBWS_PUBFIL", TRUE);
require_once "./../../soft/seytourap/inc/config.inc.php";
$ubws_dbhost = $dbhost;
$ubws_dbport = "3306";
$ubws_dbport_my = "3306";
$ubws_dbport_pg = "5432";
$ubws_dbuser = $dbuser;
$ubws_dbpass = $dbpass;
$ubws_dbname = $dbname;
$db_type = "na";
$dbname = "Not Defined";
$query  = "";
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// ERROR REPORTING
error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL ^ E_WARNING);
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// ENABLE PARSE TIMER
$ubws_cfg['misc']['loadtm'] = 1;
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// TIMER FUNCTIONS
/* ********** ********** ********** PAGE PARSE TIME ********** ********** ********** */
/*
originally by ReDuX (http://www.daniweb.com/code/snippet70.html)
*/
// timer start function
function startTimer() {
  global $starttime;
  $mtime = microtime();
  $mtime = explode(' ', $mtime);
  $mtime = $mtime[1] + $mtime[0];
  $starttime = $mtime;
}
// timer end function
function endTimer() {
  global $starttime;
  $mtime = microtime();
  $mtime = explode(' ', $mtime);
  $mtime = $mtime[1] + $mtime[0];
  $endtime = $mtime;
  $totaltime = round(($endtime - $starttime), 5);
  return $totaltime;
}
function pageLoadTime() {
  global $ubws_cfg;
  if ($ubws_cfg['misc']['loadtm'] === 1) {
    echo "<HR>Parse time: " . endTimer();
  }
}
startTimer();
/* ********** ********** ********** //PAGE PARSE TIME ********** ********** ********** */
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// DATABASE FUNCTIONS
function ubws_dbopen($db_type) {
  global $ubws_dbhost, $ubws_dbport, $ubws_dbuser, $ubws_dbpass, $ubws_dbname;
  switch($db_type) {
    case "my": {
      $ubws_dbconn = mysql_connect($ubws_dbhost . ":" . $ubws_dbport , $ubws_dbuser, $ubws_dbpass) or die ("<B>Oups! [" . mysql_errno() . "]: " .mysql_error() . "</B>");
      mysql_select_db($ubws_dbname) or die ("<B>Oups! [" . mysql_errno() . "]: " .mysql_error() . "</B>");
      break;
    }
    case "pg": {
      global $ubws_dbhost, $ubws_dbport, $ubws_dbuser, $ubws_dbpass, $ubws_dbname;
      $ubws_dbconn = pg_connect("host=" . $ubws_dbhost . " port=".$ubws_dbport." dbname=".$ubws_dbname." user=".$ubws_dbuser." password=".$ubws_dbpass."") or die("Oops! Could not connect" . pg_connection_status($ubws_dbconn));
      break;
    }
    default  : {$ubws_conn = NULL; break;}
  }
  return $ubws_dbconn;
}
function ubws_dbclose($db_type, $ubws_dbconn) {
  switch($db_type) {
    case "my": {mysql_close($ubws_dbconn); break;}
    case "pg": {pg_close($ubws_dbconn); break;}
    default  : {break;}
  }
}
function ubws_qres($db_type, $query) {
  switch($db_type) {
    case "my": {$result = mysql_query($query) or die("Oops! Query Failed. " . mysql_errno() . " : " . mysql_error()); break;}
    case "pg": {$result = pg_query($query) or die("Oops! Query Failed. " . pg_last_error()); break;}
    default  : {$result = NULL; break;}
  }
  return $result;
}
function ubws_qtotal($db_type, $result) {
  switch($db_type) {
    case "my": {$total = mysql_num_rows($result); break;}
    case "pg": {$total = pg_num_rows($result); break;}
    default  : {$total = NULL; break;}
  }
  return $total;
}
function ubws_fetch_assoc($db_type, $result) {
  switch($db_type) {
    case "my": {$row = mysql_fetch_assoc($result); break;}
    case "pg": {$row = pg_fetch_assoc($result); break;}
    default  : {$row = NULL; break;}
  }
  return $row;
}
function ubws_affected($db_type, $result) {
  switch($db_type) {
    case "my": {$affected = mysql_affected_rows(); break;}
    case "pg": {$affected = pg_affected_rows($result); break;}
    default  : {$affected = NULL; break;}
  }
  return $affected;
}
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// FORM HANDLING
if (isset($_POST['click'])) {
  $ubws_dbhost = $_POST['dbhost'];
  $ubws_dbport = $_POST['dbport'];
  $ubws_dbuser = $_POST['dbuser'];
  $ubws_dbpass = $_POST['dbpass'];
  $ubws_dbname = $_POST['dbname'];
  $query = stripslashes($_POST['query']);
  switch($_POST['db']) {
    case "my": {$db_type = "my"; $dbname = "MySQL"; break;}
    case "pg": {$db_type = "pg"; $dbname = "PostgreSQL"; break;}
    default  : {$db_type = "na"; $dbname = "Not Defined"; break;}
  }
}
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// ACTUAL PAGE CONTENT FOLLOWS
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML><HEAD><TITLE><?php echo $prog_name;?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<STYLE TYPE="text/css">
BODY {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 0px;
  border-left-width: 0px;
  border-right-width: 0px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
}
INPUT {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-right-width: 1px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
}
TEXTAREA {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-right-width: 1px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
}
INPUT.qex {
  background-color: #FFFFFF;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 0px;
  border-left-width: 0px;
  border-right-width: 0px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
}
A.reset:link {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-right-width: 1px;
  padding-top: 1px;
  padding-bottom: 1px;
  padding-left: 2px;
  padding-right: 2px;
}
A.reset:visited {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-right-width: 1px;
  padding-top: 1px;
  padding-bottom: 1px;
  padding-left: 2px;
  padding-right: 2px;
}
A.reset:hover {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-right-width: 1px;
  padding-top: 1px;
  padding-bottom: 1px;
  padding-left: 2px;
  padding-right: 2px;
}
SELECT {
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-right-width: 1px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
}
DIV.hidden {
  display: none;
  background-color: #DDDDDD;
  color: #000000;
  margin-top: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
  margin-right: 0px;
  text-align: left;
  text-decoration: none;
  font-family: verdana;
  font-weight: normal;
  font-size: 10px;
  border-color: #000000;
  border-style: solid;
  border-top-width: 0px;
  border-bottom-width: 0px;
  border-left-width: 0px;
  border-right-width: 0px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
}
</STYLE>
<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
function dbPort() {
  var element_from = document.forms['dbform'].db;
  var element_to = document.forms['dbform'].dbport;
  var new_value;
  if (element_from.value == 'my') {
    new_value = '<?php echo $ubws_dbport_my;?>';
  }
  else if (element_from.value == 'pg') {
    new_value = '<?php echo $ubws_dbport_pg;?>';
  }
  else {
    new_value = <?php echo $ubws_dbport;?>;
  }
  element_to.value = new_value;
}
function dbPortDef(dbtype) {
  var element_from = document.forms['dbform'].db;
  var element_to = document.forms['dbform'].dbport;
  var new_value;
  if (dbtype == 'my') {
    new_value = '<?php echo $ubws_dbport_my;?>';
  }
  else if (dbtype == 'pg') {
    new_value = '<?php echo $ubws_dbport_pg;?>';
  }
  element_to.value = new_value;
}
function copyvalue(element_from,element_to) {
  element_to.value = element_from.value;
}
/* 
http://www.netlobo.com
*/
function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) // this is the way the standards work
    elem = document.getElementById( whichLayer );
  else if( document.all ) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if( document.layers ) // this is the way nn4 works
    elem = document.layers[whichLayer];
  vis = elem.style;
  // if the style.display value is blank we try to figure it out here
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}
</SCRIPT>
</HEAD>
<BODY>
<DIV ALIGN="center">
<H2 STYLE="color: #000000"><?php echo $prog_name . " v." . $prog_version;?></H2>
<H3 STYLE="color: #000000"><?php echo $prog_desc;?></H3>
<H3 STYLE="color: #990000">WARNING! NO SECURITY! INTERNAL/TRUSTED USE ONLY!</H3>
<HR>
<H3 STYLE="color: #000000">Query Form:</H3>
<FORM NAME="dbform" ID="dbform" METHOD="POST" ACTION="">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" ALIGN="center">
<TR>
  <TD>
    <TABLE BORDER="0" CELLPADDING="3" CELLSPACING="0" ALIGN="center">
    <TR>
      <TD ALIGN="right">
        <B>host:</B>
      </TD>
      <TD ALIGN="left">
        <INPUT TYPE="text" NAME="dbhost" ID="dbhost" SIZE="10" MAXLENGTH="20" VALUE="<?php echo $ubws_dbhost;?>">
      </TD>
    </TR>
    <TR>
      <TD ALIGN="right">
        <B>port:</B>
      </TD>
      <TD ALIGN="left">
        <INPUT TYPE="text" NAME="dbport" ID="dbport" SIZE="10" MAXLENGTH="20" VALUE="<?php echo $ubws_dbport;?>">
      </TD>
    </TR>
    <TR>
      <TD ALIGN="right">
        <B>user:</B>
      </TD>
      <TD ALIGN="left">
        <INPUT TYPE="text" NAME="dbuser" ID="dbuser" SIZE="10" MAXLENGTH="20" VALUE="<?php echo $ubws_dbuser;?>">
      </TD>
    </TR>
    <TR>
      <TD ALIGN="right">
        <B>password:</B>
      </TD>
      <TD ALIGN="left">
        <INPUT TYPE="password" NAME="dbpass" ID="dbpass" SIZE="10" MAXLENGTH="20" VALUE="<?php echo $ubws_dbpass;?>">
      </TD>
    </TR>
    <TR>
      <TD ALIGN="right">
        <B>database:</B>
      </TD>
      <TD ALIGN="left">
        <INPUT TYPE="text" NAME="dbname" ID="dbname" SIZE="10" MAXLENGTH="20" VALUE="<?php echo $ubws_dbname;?>">
      </TD>
    </TR>
    </TABLE>
  </TD>
  <TD VALIGN="top">
  <TABLE BORDER="0" CELLPADDING="3" CELLSPACING="0" ALIGN="center">
  <TR>
    <TD ALIGN="right">
      &#160;
    </TD>
    <TD ALIGN="left">
      &#160;
    </TD>
  </TR>  
  <TR>
    <TD ALIGN="right">
      <B>Database type:</B>
    </TD>
    <TD ALIGN="left">
      <SELECT NAME="db" ID="db" onChange="dbPort();">
        <OPTION VALUE="my" <?php // firefox only -> ?>onMouseOver="dbPortDef('my');"<?php if ($db_type == "my") {echo " SELECTED";}?>>MySQL</OPTION>
        <OPTION VALUE="pg" <?php // firefox only -> ?>onMouseOver="dbPortDef('pg');"<?php if ($db_type == "pg") {echo " SELECTED";}?>>PostgreSQL</OPTION>
      </SELECT>
    </TD>
  </TR>
  <TR>
    <TD ALIGN="right">
      <B>Query:</B>
    </TD>
    <TD ALIGN="left">
      <TEXTAREA NAME="query" ID="query" ROWS="3" COLS="100"><?php echo $query;?></TEXTAREA>
    </TD>
  </TR>
  <TR>
    <TD ALIGN="right">
      <!--Test it:-->&#160;
    </TD>
    <TD ALIGN="left">
      <INPUT TYPE="submit" NAME="click" ID="click" VALUE="click">
      &#160;
      <A CLASS="reset" HREF="<?php echo $prog_file;?>">reset</A>
    </TD>
  </TR>
  <TR>
    <TD ALIGN="right">
      &#160;
    </TD>
    <TD ALIGN="left">
      &#160;
    </TD>
  </TR>
  </TABLE>
  </TD>
</TR>
</TABLE>
</FORM>
<HR>
<H3 STYLE="color: #000000">Query Results:</H3>
<?php
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// QUERY RESULTS
if ($db_type !== "na") {
  ubws_dbopen($db_type);

  $result = ubws_qres($db_type, $query);

  $posit = stripos($query, "SELECT");
  if ($posit !== false) {$total = ubws_qtotal($db_type, $result);} else {$total = '0';}
  
  $affected = ubws_affected($db_type, $result);

  echo "\n<BR>" . $affected . " row(s) affected:\n<BR><BR>";
  
  if (preg_match("/INSERT|DELETE|ALTER|UPDATE|CREATE|DROP/i", $query)) {$insdel = '1';} else {$insdel = '0';}
  
  if ($affected != '0' && ($total == '0' && $insdel == '0')) {
    echo "\n<TABLE BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"2\">";
    for ($j=1; $j<=$affected; $j++) {
      $row = ubws_fetch_assoc($db_type, $result);
      
      if ($j === 1) {
      echo "\n\t<TR><TD STYLE=\"background-color: #EEEEEE; text-align: center; vertical-align: middle;\"><B>#</B></TD>";
      foreach ($row as $key => $value) {
        echo "\n\t\t<TD STYLE=\"background-color: #EEEEEE; text-align: center; vertical-align: middle;\"><B>" . $key . "</B></TD>";
      }
      echo "\n\t</TR>";
      
      }
      echo "\n\t<TR><TD STYLE=\"background-color: #EEEEEE; text-align: center; vertical-align: middle;\">".$j."</TD>";
      foreach ($row as $key => $value) {
        echo "\n\t\t<TD STYLE=\"background-color: #FFFFFF; text-align: center; vertical-align: middle;\">" . $value . "</TD>";
      }
      echo "\n\t</TR>";
    }
    echo "</TABLE>";
  }
  echo "\n<BR>" . $total . " result(s):\n<BR><BR>";
  if ($total !== '0') {
    echo "\n\n<TABLE BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"2\">";
    for ($i=1; $i<=$total; $i++) {
      $row = ubws_fetch_assoc($db_type, $result);
      
      if ($i === 1) {
      echo "\n\t<TR><TD STYLE=\"background-color: #EEEEEE; text-align: center; vertical-align: middle;\"><B>#</B></TD>";
      foreach ($row as $key => $value) {
        echo "\n\t\t<TD STYLE=\"background-color: #EEEEEE; text-align: center; vertical-align: middle;\"><B>" . $key . "</B></TD>";
      }
      echo "\n\t</TR>";
      
      }
      echo "\n\t<TR><TD STYLE=\"background-color: #EEEEEE; text-align: center; vertical-align: middle;\">".$i."</TD>";
      foreach ($row as $key => $value) {
        echo "\n\t\t<TD STYLE=\"background-color: #FFFFFF; text-align: center; vertical-align: middle;\">" . $value . "</TD>";
      }
      echo "\n\t</TR>";
    }
    echo "</TABLE>";
  }

  ubws_dbclose($db_type, ubws_dbopen($db_type));
}
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
?>
<HR>
<H3 STYLE="color: #000000">Query Examples:</H3>
<A STYLE="color: #000000;" HREF="javascript:toggleLayer('hiddenDiv1');">show/hide</A>
<BR>
<DIV ID="hiddenDiv1" CLASS="hidden">
<?php
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
// QUERY EXAMPLES
$query_ds['1'] = "Show databases";
$query_my['1'] = "SHOW DATABASES;";
$query_pg['1'] = "SELECT datname FROM pg_database;";

$query_ds['2'] = "Show tables";
$query_my['2'] = "SHOW TABLES;";
$query_pg['2'] = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';";

$query_ds['3'] = "Show columns";
$query_my['3'] = "SHOW COLUMNS FROM tablename;";
$query_pg['3'] = "SELECT column_name FROM information_schema.columns WHERE table_name = 'tablename';";

$query_ds['4'] = "Create table";
$query_my['4'] = "CREATE TABLE tablename (id INT NOT NULL PRIMARY KEY, value VARCHAR(20) NOT NULL);";
$query_pg['4'] = "CREATE TABLE tablename (id INT NOT NULL PRIMARY KEY, value VARCHAR(20) NOT NULL);";

$query_ds['5'] = "Drop table";
$query_my['5'] = "DROP TABLE tablename;";
$query_pg['5'] = "DROP TABLE tablename;";

$query_ds['6'] = "Insert one row";
$query_my['6'] = "INSERT INTO tablename (id, value) VALUES (1, 'value 1');";
$query_pg['6'] = "INSERT INTO tablename (id, value) VALUES (1, 'value 1');";

$query_ds['7'] = "Insert multiple rows";
$query_my['7'] = "INSERT INTO tablename (id, value) VALUES (1, 'value 1'), (2, 'value 2'), (3, 'value 3'), (4, 'value 4');";
$query_pg['7'] = "INSERT INTO tablename (id, value) SELECT 1, 'value1' UNION SELECT 2, 'value 2' UNION SELECT 4, 'value 4';";

$query_ds['8'] = "Modify data";
$query_my['8'] = "UPDATE tablename SET value = 'value 1 mod' WHERE id = '1';";
$query_pg['8'] = "UPDATE tablename SET value = 'value 1 mod' WHERE id = '1';";

$query_ds['9'] = "Delete data";
$query_my['9'] = "DELETE FROM tablename WHERE id = '1';";
$query_pg['9'] = "DELETE FROM tablename WHERE id = '1';";

$query_ds['10'] = "Empty table";
$query_my['10'] = "TRUNCATE TABLE tablename;";
$query_pg['10'] = "TRUNCATE TABLE tablename;";

$query_ds['11'] = "Create table (auto increment)";
$query_my['11'] = "CREATE TABLE tablename (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, value VARCHAR(20) NOT NULL);";
$query_pg['11'] = "CREATE TABLE tablename (id SERIAL NOT NULL PRIMARY KEY, value VARCHAR(20) NOT NULL);";

$query_ds['12'] = "Drop table (auto increment)";
$query_my['12'] = "DROP TABLE tablename;";
$query_pg['12'] = "DROP TABLE tablename;";

$query_ds['13'] = "Insert one row (auto increment)";
$query_my['13'] = "INSERT INTO tablename (value) VALUES ('value 1');";
$query_pg['13'] = "INSERT INTO tablename (value) VALUES ('value 1');";

$query_ds['14'] = "Insert multiple rows (auto increment)";
$query_my['14'] = "INSERT INTO tablename (value) VALUES ('value 1'), ('value 2'), ('value 3'), ('value 4');";
$query_pg['14'] = "INSERT INTO tablename (value) SELECT 'value1' UNION SELECT 'value 2' UNION SELECT 'value 4';";

$query_ds['15'] = "Show data";
$query_my['15'] = "SELECT * FROM tablename;";
$query_pg['15'] = "SELECT * FROM tablename;";

$query_ds['16'] = "Show users";
$query_my['16'] = "SELECT user FROM mysql.user;";
$query_pg['16'] = "SELECT * FROM pg_user;";

$query_ds['17'] = "Create user";
$query_my['17'] = "GRANT USAGE ON *.* TO 'test'@'localhost' IDENTIFIED BY 'REDACTED' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;";
$query_pg['17'] = "CREATE ROLE test LOGIN PASSWORD 'test' NOSUPERUSER NOINHERIT CREATEDB NOCREATEROLE VALID UNTIL 'infinity';";

$query_ds['18'] = "Change password";
$query_my['18'] = "SET PASSWORD FOR 'test'@'localhost' = PASSWORD('REDACTED');";
$query_pg['18'] = "ALTER Role test PASSWORD 'test2';";

$query_ds['19'] = "Delete user";
$query_my['19'] = "DROP USER 'test'@'localhost';";
$query_pg['19'] = "DROP Role test;";

$query_ds['20'] = "Create database";
$query_my['20'] = "CREATE DATABASE test DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci; GRANT ALL PRIVILEGES ON test.* TO 'test'@'localhost' WITH GRANT OPTION;";
$query_pg['20'] = "CREATE DATABASE test WITH OWNER = test ENCODING = 'UTF8' TABLESPACE = pg_default;";

$query_ds['21'] = "Delete database";
$query_my['21'] = "DROP DATABASE test;";
$query_pg['21'] = "DROP DATABASE test;";

$query_ds['22'] = "Optimize Table";
$query_my['22'] = "OPTIMIZE TABLE cursvaltab;";
$query_pg['22'] = "";

$totalex = count($query_ds);
/* ********** ********** ********** ********** ********** ********** ********** ********** ********** ********** */
?>
<BR>
<DIV ALIGN="center">[click to use]</DIV>
<FORM NAME="queryform" ID="queryform" METHOD="POST" ACTION="">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="2" ALIGN="center">
<TR>
  <TD STYLE="width:100px; background-color: #EEEEEE; text-align: center; vertical-align: middle; font-size: 10px;">
    <B>Query</B>
  </TD>
  <TD STYLE="width:450px; background-color: #EEEEEE; text-align: center; vertical-align: middle; font-size: 10px;">
    <B>MySQL</B>
  </TD>
  <TD STYLE="width:450px; background-color: #EEEEEE; text-align: center; vertical-align: middle; font-size: 10px;">
    <B>PostgreSQL</B>
  </TD>
</TR>
<?php
for ($ex=1; $ex<=$totalex; $ex++) {
?>
<TR>
  <TD STYLE="width:220px; background-color: #EEEEEE; text-align: center; vertical-align: middle; font-size: 10px;">
    <?php echo $query_ds[$ex];?>
  </TD>
  <TD STYLE="background-color: #FFFFFF; text-align: center; vertical-align: middle;">
    <INPUT CLASS="qex" TYPE="text" NAME="query_my_<?php echo $ex; ?>" ID="query_my_<?php echo $ex; ?>" VALUE="<?php echo $query_my[$ex];?>" SIZE="90" MAXLENGTH="255" onClick="JavaScript:copyvalue(document.forms['queryform'].query_my_<?php echo $ex; ?>,document.forms['dbform'].query);">
  </TD>
  <TD STYLE="background-color: #FFFFFF; text-align: center; vertical-align: middle;">
    <INPUT CLASS="qex" TYPE="text" NAME="query_pg_<?php echo $ex; ?>" ID="query_pg_<?php echo $ex; ?>" VALUE="<?php echo $query_pg[$ex];?>" SIZE="90" MAXLENGTH="255" onClick="JavaScript:copyvalue(document.forms['queryform'].query_pg_<?php echo $ex; ?>,document.forms['dbform'].query);">
  </TD>
</TR>
<?php
}
?>
</TABLE>
</FORM>
</DIV>
<?php pageLoadTime(); ?>
</DIV>
</BODY>
</HTML>