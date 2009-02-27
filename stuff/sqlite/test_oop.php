<html>
<head></head>
<body>
<?php
// set path of database file 
$file = str_replace('\\', '/', dirname(__FILE__)) . '\library.sqlite';


// create database object
$db = new SQLiteDatabase($file) or die("Could not open database");

// generate query string
$query = "SELECT *  FROM books";

// execute query
// return result object
$result = $db->query($query) or die("Error in query");

// if rows exist
if ($result->numRows() > 0) {
    // get each row as an array
    // print values
    echo "<table cellpadding=10 border=1>";
    while($row = $result->fetch()) {
        echo "<tr>";
        echo "<td>".$row[0]."</td>";
        echo "<td>".$row[1]."</td>";
        echo "<td>".$row[2]."</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// all done
// destroy database object
unset($db);
?>
</body>
</html>