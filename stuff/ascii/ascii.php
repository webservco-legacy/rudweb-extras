<html>
<head>
<title>Ascii</title>
<style>
/* formatari pentru marimea caracterelor care va forma imaginea. Pentru  ca imginea sa-si pastreze aspect-ratio line-height trebuie sa fie 0.5 din font */
body{
    line-height:3px;
    font-size:6px;
}
</style>
</head>
<body>
<?php
/* functia care citeste extensia imaginii */
function getext($filename) {
    $pos = strrpos($filename,'.');
    $str = substr($filename, $pos);
    return $str;
}
/* daca nu este apasat butonul de submit afisam formularul, iar daca este  afisat  executam codul de  creare  a  imaginii ASCII */
if(!isset($_POST['submit'])){
?>
<form action="<?echo $_SERVER['PHP_SELF'];?>" method="post">
    URL imagine: <input type="text" name="image"><br>
    <input type="submit" name="submit" value="Create">
</form>
<?
}else{
    $image = $_POST['image'];
    $ext = getext($image); //punem extensia intr-o variabila
//verificam daca este jpg  
if($ext == ".jpg"){
        $img = ImageCreateFromJpeg($image);
    }
    else{
        echo'Tipul Fisierului este gresit';
    }
//citim dimensiunile imaginii
    $width = imagesx($img);
    $height = imagesy($img);
// am ales sa afisez din 6 in 6 pixeli     
    for($h=0;$h<$height;$h++){ if($h%6==0) {
        for($w=0;$w<=$width;$w++){ if($w%6==0) {
           // trecem culorile fiecarui pixel in cate o variabila     
            $rgb = ImageColorAt($img, $w, $h);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
           // afisam cate un caracter de culoarea pixelului
            echo '<span style="color:rgb('.$r.','.$g.','.$b.');">#</span>';
            $widthi=$width-5;
            
            if($w >= $widthi){
                echo '<br>';
            }
        }    
        }
        }
    }
}
?>  
</body>
</html> 