<?php 
///////////////////////////////////////////////////////////////////////////////////////////
// MySQL Dump Utility v1.4 - februari 4th, 2005 - for MySQL versions 3.23.3x and higher which
// support the "CREATE TABLE" function
//
// Based upon the Dump Utility from Transatlantic-Web (original Author) http://www.taw24.de
//
// Modified by Benjamin Klatt <benjamin.klatt@raytion.com>
//
// Heavily modified / re-progged by Erik van Geene <info@virogo.com>
///////////////////////////////////////////////////////////////////////////////////////////


// If this require fails, please provide the full path
//require ("/your/full/path/include/db_configSQL.php");

require ("./include/db_configSQL.php");

$ProgVersion	= PROG_VERSION;
$StoragePath	= STORAGE_PATH;
$ProcesTime		= EXTRA_TIME;
$UseGzip		= USE_GZIP;
$UseMail		= USE_MAIL;
$MailTo		= MAIL_TO;
$MailFrom		= MAIL_FROM;
$KeepFile		= KEEP_FILE;
$DbCon		= DB_CONNECT;
$Dbusr		= DB_USR;
$Dbpass		= DB_PASSWD;
$Db			= DB_NAME;
$Linebuffer		= LINE_BUFFER;
$Exclude		= '$ExcludeTables = array(' . EXCLUDE_TABLES . ');';

eval($Exclude);

if (!($Dblink = mysql_connect($DbCon, $Dbusr, $Dbpass)))
{
	echo "Could not open a MySQL connection";
	exit;
}

if (!mysql_select_db($Db, $Dblink))
{
	echo "Could not connect to database $Db";
	exit;
}

$TimeOfBackup = date("d-m-Y H:i");
$NameOfBackup = date("Ymd-H") . "h.sql";

$FileName = $StoragePath . "/" . $NameOfBackup;

$WriteLines  = "# MySQL Dump Utility $ProgVersion\n";
$WriteLines .= "# Backup at $TimeOfBackup\n";
$WriteLines .= "# Database: $Db\n\n";

if (file_exists($FileName))
	unlink($FileName);

$fd = fopen($FileName,"a+");
fwrite($fd, $WriteLines); 
fclose($fd);     

HTMLHeader();

HTMLStart($TimeOfBackup,$Db);

flush();
 
$TableArray = array();
$TableCount = 0;
$ListTableResult = mysql_list_tables($Db);

for($x = 0; $x < mysql_num_rows($ListTableResult); $x++) 
{ 	
	$TableName = mysql_tablename($ListTableResult,$x);

	if ($TableName != "" && !in_array($TableName, $ExcludeTables))
	{
		$TableArray[$TableCount] = mysql_tablename($ListTableResult,$x);
		$TableCount++;
	}
}								 

if (!@set_time_limit($ProcesTime))
	ini_set ("max_execution_time" , "$ProcesTime");

for ($count = 0; $count < $TableCount; $count++)
{
	$Table = $TableArray[$count];
	$TableName = "# TABLE: $Table\n";

	echo "Processing table $Table\n";

	$CreateTableResult = mysql_query("SHOW CREATE TABLE $Table");
	$CreateTableLines = mysql_fetch_row ($CreateTableResult);

	$WriteLines .= "DROP TABLE IF EXISTS $Table;\n\n";

	if (!preg_match("/;$/",$CreateTableLines[1])) // Make sure it ends with ';'
		$CreateTableLines[1] .= ";\n\n";

	$WriteLines .= $CreateTableLines[1];

	$fd = fopen($FileName,"a+");
	fwrite($fd, $TableName.$WriteLines);
	fclose($fd);

	unset($WriteLines);

	if ($Table != "")
	{
		$fd = fopen($FileName,"a+");
		$SelectResult = mysql_query("SELECT * FROM $Table");
		$RowCount = mysql_num_rows ($SelectResult);
		$FieldCount = mysql_num_fields($SelectResult);

   
		for ($i=0; $i < $RowCount; $i++)
		{
			unset($WriteLines);
			$ReadLine = mysql_fetch_array($SelectResult); 
			$WriteLines = "";
			$WriteLines .= "INSERT INTO $Table VALUES (";

			for ($k=0; $k < $FieldCount; $k++)
			{
				$procesline = addslashes($ReadLine[$k]);
				$procesline = ereg_replace("\r",'\r',$procesline);
				$procesline = ereg_replace("\n",'\n',$procesline);

				if($k == ($FieldCount - 1))
					$WriteLines .= "'".$procesline."'";
				else
					$WriteLines .= "'".$procesline."',";
			}
			$WriteLines .= ");\n";
			fwrite($fd, $WriteLines);
		}
		$WriteLines = "\n";
		fwrite($fd, $WriteLines);
		fclose($fd);

	}
}

if ($UseGzip == "Y")
{
	$GetData = fopen ($FileName,"r");

	$GZFileName = $FileName . ".gz";
	$NameOfBackup = $NameOfBackup . ".gz";

	$PutData = gzopen ($GZFileName,"w");

	while(!feof($GetData))
	{
		$line = fgets($GetData, $Linebuffer);
		gzwrite ($PutData, $line);
	}

	fclose($GetData);
	gzclose($PutData);

	if (file_exists($FileName))
		unlink($FileName);
}

if($UseMail == "Y" && $UseGzip == "Y") // If not gzipped, don't send it, mailbox will be full very fast...
{
	$FileType = filetype($GZFileName);
		
	$file = fopen($GZFileName,"rb");
	$file_content = fread($file,filesize($GZFileName));
	fclose($file);
	
	$file_content = chunk_split(base64_encode($file_content)); 

	$boundary = strtoupper(md5(uniqid(time()))); 

	// create the message header... 
	$header .= "From: ". $MailFrom . "\n"; 
	$header .= "MIME-version: 1.0\n"; 
	$header .= "Content-Type: multipart/mixed;\n"; 
	$header .= "\tboundary= " . $boundary . "\n\n"; 
	$header .= "This is a multi-part message in MIME format.\n\n"; 

	// create the message body in the header 
	$header .= "--" . $boundary . "\n"; 
	$header .= "Content-Type: " . "text/html" . ";\n"; 
	$header .= "\tcharset=\"iso-8859-1\"\n"; 
	$header .= "Content-Transfer-Encoding: quoted-printable\n\n"; 

	// actual message 
	$header .= "You have received an SQL-backup of your database\n\n"; 

	// now for the attachement 
	$header .= "--" . $boundary . "\n"; 
	$header .= "Content-Type: application/octet- stream\n"; 
	$header .= "Content-Transfer-Encoding: base64\n"; 
	$header .= "Content-Disposition: attachment; filename=\"" . $NameOfBackup . "\"\n\n"; 

	// actual attachement 
	$header .= $file_content . "\n\n"; 
	$header .= "--" . $boundary . "--"; 

	//send mail 
	$SendMail = @mail($MailTo, "MySQL backup on $TimeOfBackup", "", $header);
	if (!$SendMail)
	{
		$KeepFile="Y";
		echo "\nError sending mail, backup remains on server...\n";
	}
	else
		echo "\nBackup was send by mail...\n";

}

if ($KeepFile == "N")
{
	if (file_exists($FileName))
		unlink($FileName);

	if (file_exists($GZFileName))
		unlink($GZFileName);
}

if ($UseGzip == "Y")
	HTMLFooter($GZFileName, $NameOfBackup);
else
	HTMLFooter($FileName, $NameOfBackup);


///////////////////////////////////////////////////////////////////////////////////////////
// CODE ENDS HERE. FUNCTIONS LEFT ONLY ////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

function HTMLHeader()
{
	echo <<<HTMLHeader
<HTML>
<HEAD>
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" marginheight="0" marginwidth="0">
<FONT SIZE=3 FACE=Arial><B>SQL-DATA BACKUP TOOL</B></FONT>
<PRE>
With this utility you can save an SQL-dump like phpMyAdmin does. It will store
the SQL-file on the same location where you upload your SQL-files with the
db_RestoreSQL-utility. Here you can select it thru a dropdown menu.

This SQL-dump contains DROP TABLE-statements. You can de-activate certain tables
by placing the names in the configuration-part in this script.

THIS SCRIPT IS LIMITED BY THE MAX_EXECUTION_TIME OF YOUR PHP-CONFIGURATION.
IF YOUR SERVER RUNS IN SAFE MODE, YOU MIGHT RUN OUT OF TIME.....

---------------------------------------------------------------------------------

HTMLHeader;
}

function HTMLStart($Time, $Dbase)
{
	echo <<<HTMLStart
Starting backup...

Time  :   $Time
DBase :   $Dbase

HTMLStart;
}

function HTMLFooter($File, $Name)
{

	$FileSize = round(filesize($File) / 1024,2);

	echo "\nFile  :   $Name [$FileSize KB]\n";
	echo "\nBackup finished...\n";
	echo "</PRE>\n</BODY>\n</HTML>\n";
}

?>