<?php
/*
./pub/temp/ftp.php
since: 10:48 2008-05-28

*/
?>
<?php
// set up basic connection
$ftp_server = '80.96.77.207';
$ftp_port   = '21';
$conn_id = ftp_connect($ftp_server, $ftp_port); 

// login with username and password
$ftp_user_name = 'dasoft';
$ftp_user_pass = 'REDACTED';
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

// check connection
if ((!$conn_id) || (!$login_result)) { 
       echo "<BR>FTP connection has failed!";
       echo "<BR>Attempted to connect to $ftp_server for user $ftp_user_name"; 
       exit; 
   } else {
       echo "<BR>Connected to $ftp_server, for user $ftp_user_name";
    }

// turn passive mode on
$ftp_passive = ftp_pasv($conn_id, true);

// current directory
echo "<BR>Current directory: " . ftp_pwd($conn_id) . "\n";

// try to change the directory
$chdir = 'pub/temp';
if (ftp_chdir($conn_id, $chdir)) {
   echo "<BR>Current directory is now: " . ftp_pwd($conn_id) . "\n";
} else { 
   echo "<BR>Couldn't change directory\n";
}

// upload the file
$source_file = './ftp.php';
$destination_file = './ftp.php';
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 

// check upload status
if (!$upload) { 
       echo "<BR>FTP upload has failed!";
   } else {
       echo "<BR>Uploaded $source_file to $ftp_server as $destination_file";
   }


   
// close the FTP stream 
ftp_close($conn_id); 
?>