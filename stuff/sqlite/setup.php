<html>
<head></head>
<body> 
<?php
// set path of database file 
$db = str_replace('\\', '/', dirname(__FILE__)) . '\library.sqlite';

echo $db;

// open database file 
$handle = sqlite_open($db) or die("Could not open database");

// structure
$query1 = "create table books (id integer primary key, title varchar(255) not null, author varchar(255) not null)"; 
// values
$query2 = "insert into books (title, author) values ('The Lord Of The Rings', 'J.R.R. Tolkien')"; 
$query3 = "insert into books (title, author) values ('The Murders In The Rue Morgue', 'Edgar Allen Poe')"; 
$query4 = "insert into books (title, author) values ('Three Men In A Boat', 'Jerome K. Jerome')"; 
$query5 = "insert into books (title, author) values ('A Study In Scarlet', 'Arthur Conan Doyle')"; 
$query6 = "insert into books (title, author) values ('Alice In Wonderland', 'Lewis Carroll')"; 

sqlite_query($handle, $query1) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle))); 
sqlite_query($handle, $query2) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle))); 
sqlite_query($handle, $query3) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle))); 
sqlite_query($handle, $query4) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle))); 
sqlite_query($handle, $query5) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle))); 
sqlite_query($handle, $query6) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle))); 

// all done
// close database file
sqlite_close($handle); 
?>
</body>
</html>