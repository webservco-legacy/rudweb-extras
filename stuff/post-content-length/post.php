<?php
/**
 * Daca functia error_get_last exista, 
 * variabila $error va contine ultima eroare aparuta
 */
$error = function_exists('error_get_last') ? error_get_last() : NULL;
/**
 * Daca avem o eroare si este cea pe care o cautam...
 */
if(is_array($error) && (FALSE !== strpos($error['message'], 'POST Content-Length of')))
{
  /**
   * Facem ceva
   */
   //trigger_error('Fisierul este prea mare', E_USER_ERROR);
}
?>
<html>
<head>
  <title>test</title>
</head>
<body>
<form enctype="multipart/form-data" name="myForm" id="myForm" action="" method="post">
  <input type="text" name="myText" id="myText" maxlength="30" value="" /><br />
  <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><!-- 2MB -->
  <input type="file" name="myFile" id="myFile" size="1" /><br />
  <input type="submit" name="mySubmit" id="mySubmit" value="Submit" />
</form>
<pre>
post: <?php var_dump($_POST); ?>
files: <?php var_dump($_FILES); ?>
error: <?php var_dump($error); ?>
</pre>
</body>
</html>