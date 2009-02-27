<?php
// pagina existenta pe server pagina.php 
if (isset($_GET['p']))
    {
    $pagina = $_GET['p'];
    echo $pagina;
    if (is_file($pagina))
        {
        include($pagina);
        }
    else
        {
        echo '<br />A fost ceruta pagina '.$pagina.' dar nu exista pe server.';
        }
    }
else
    {
    echo '<br />Alegeti o pagina';
    }     

?>