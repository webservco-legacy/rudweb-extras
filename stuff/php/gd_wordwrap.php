<?php
/*
final result used in manrulate/pdf
*/



// vegard at I dot DONT dot WANT dot SPAM dot programmer dot no (04-Apr-2005 02:37)
//Here is a function that wordwraps text you want to print, allows to specify where the text should be printed, 
what the maximum width should be, and returns the height used.

       function printWordWrapped(&$image, $top, $left, $maxWidth, $font, $color, $text, $textSize) {
               $words = explode(' ', strip_tags($text)); // split the text into an array of single words
               $line = '';
               while (count($words) > 0) {
                       $dimensions = imagettfbbox($textSize, 0, $font, $line.' '.$words[0]);
                       $lineWidth = $dimensions[2] - $dimensions[0]; // get the length of this line, if the word is to be included
                       if ($lineWidth > $maxWidth) { // if this makes the text wider that anticipated
                               $lines[] = $line; // add the line to the others
                               $line = ''; // empty it (the word will be added outside the loop)
                               }
                       $line .= ' '.$words[0]; // add the word to the current sentence
                       $words = array_slice($words, 1); // remove the word from the array
                       }
               if ($line != '') { $lines[] = $line; } // add the last line to the others, if it isn't empty
               $lineHeight = $dimensions[1] - $dimensions[7]; // the height of a single line
               $height = count($lines) * $lineHeight; // the height of all the lines total
               // do the actual printing
               $i = 0;
               foreach ($lines as $line) {
                       imagettftext($image, $textSize, 0, $left, $top + $lineHeight * $i, $color, $font, $line);
                       $i++;
                       }
               return $height;
               }
?>
<?php
/*
miles at dinewilmingtononline dot com (13-Jul-2005 02:29)
Here is a slight modification to the function posted below that vegard posted that allows for left, 
right or center alignment within the text boxes.  
$halign has a default of left.  center, and right can also be put in.
*/
function printWordWrapped(&$image, $top, $left, $maxWidth, $font, $color, $text, $textSize, $halign="left") {
$words = explode(' ', strip_tags($text)); // split the text into an array of single words
$line = '';
while (count($words) > 0) {
$dimensions = imagettfbbox($textSize, 0, $font, $line.' '.$words[0]);
$lineWidth = $dimensions[2] - $dimensions[0]; // get the length of this line, if the word is to be included
       if ($lineWidth > $maxWidth) { // if this makes the text wider that anticipated
$lines[] = $line; // add the line to the others
$line = ''; // empty it (the word will be added outside the loop)
       }
       $line .= ' '.$words[0]; // add the word to the current sentence
       $words = array_slice($words, 1); // remove the word from the array
   }
   if ($line != '') { $lines[] = $line; } // add the last line to the others, if it isn't empty
   $lineHeight = $dimensions[1] - $dimensions[7]; // the height of a single line
   $height = count($lines) * $lineHeight; // the height of all the lines total
   // do the actual printing
   $i = 0;
//print_R($widths);
   foreach ($lines as $line) {
if($halign=="center") {
//figure out width of line
$dimensions = imagettfbbox($textSize, 0, $font, $line);
$lineWidth = $dimensions[2] - $dimensions[0];
//figure out where the center is.
$center=floor($maxWidth/2 + $left);
$leftStart=$center-$lineWidth/2;
} else if ($halign=="right") {
//figure out width of line
$dimensions = imagettfbbox($textSize, 0, $font, $line);
$lineWidth = $dimensions[2] - $dimensions[0];
$leftStart=$left+$maxWidth-$lineWidth;
} else {
$leftStart=$left;
} 
imagettftext($image, $textSize, 0, $leftStart, $top + $lineHeight * $i, $color, $font, $line);
       $i++;
}
return $height;
}
?>
<?php
/*
thetorpedodog [gmail] (28-Jul-2005 05:09)

Just for the sake of consistency, I turned Miles' function into one that takes a $right value rather than a $maxWidth value. 
This makes it like all the other image* functions.
*/
function imageprintWordWrapped(&$image, $top, $left, $right, $font, $color, $text, $textSize, $halign="left") {
  $maxWidth = $right - $left ;    //the trivial change
  $words = explode(' ', strip_tags($text)); // split the text into an array of single words
  $line = '';
  while (count($words) > 0) {
      $dimensions = imagettfbbox($textSize, 0, $font, $line.' '.$words[0]);
      $lineWidth = $dimensions[2] - $dimensions[0]; // get the length of this line, if the word is to be included
      if ($lineWidth > $maxWidth) { // if this makes the text wider that anticipated
          $lines[] = $line; // add the line to the others
          $line = ''; // empty it (the word will be added outside the loop)
      }
      $line .= ' '.$words[0]; // add the word to the current sentence
      $words = array_slice($words, 1); // remove the word from the array
  }
  if ($line != '') { $lines[] = $line; } // add the last line to the others, if it isn't empty
  $lineHeight = $dimensions[1] - $dimensions[7]; // the height of a single line
  $height = count($lines) * $lineHeight; // the height of all the lines total
  // do the actual printing
  $i = 0;
  //print_R($widths);
  foreach ($lines as $line) {
      if($halign=="center") {
          //figure out width of line
          $dimensions = imagettfbbox($textSize, 0, $font, $line);
          $lineWidth = $dimensions[2] - $dimensions[0];
          //figure out where the center is.
          $center=floor($maxWidth/2 + $left);
          $leftStart=$center-$lineWidth/2;
      } else if ($halign=="right") {
          //figure out width of line
          $dimensions = imagettfbbox($textSize, 0, $font, $line);
          $lineWidth = $dimensions[2] - $dimensions[0];
          $leftStart=$left+$maxWidth-$lineWidth;
      } else {
          $leftStart=$left;
      }   
      imagettftext($image, $textSize, 0, $leftStart, $top + $lineHeight * $i, $color, $font, $line);
      $i++;
  }
  return $height;
}
?>
<?php
/*
fernandez_marce at yahoo dot com (03-Aug-2005 06:53)

I would modify the last function imageprintWordWrapped() posted below. When it says:
// do the actual printing
$i = 0;
$i should be equal to 1, because if $top is greater than 0, the imagettftext() call will not work for the first element in the $lines array ($top + lineHeight*$i) == $top when $i=0!
imagettftext($image, $textSize, 0, $leftStart, $top + $lineHeight * $i, $color, $font, $line);
$i++;
To sum up, replace
// do the actual printing
$i = 0;
with:
// do the actual printing
$i = 1;
Cheers,
Marcelo
*/
?>
<?php
/*
c.scheffers [gmail] (14-Aug-2005 09:15)

I would like to post a small modification to the imageprintWordWrapped() posted below also.
The following code causes each line to start with a space, which results in the incorrect alignment of text:
$line .= ' '.$words[0]; // add the word to the current sentence
A (quick) fix for this problem:
$line .= ($line != '' ? ' ' : '').$words[0]; // add the word to the current sentence

*/
?>
<?php
/*
Nimja (13-Jan-2006 02:01)

This is a completely rewritten function of "printWordWrapped" because it lacked a few important features. First of all, it started too high with it's text, which was easily fixed. And it has the annoying 'feature' of adding a space in front of every line. Not ideal.
But secondly, and more importantly, it didn't support long words or linebreaks. This is all fixed. And I rewrote it for cleaner code.
Please note that with the current code you could easily make a vertical align as well if you wanted to. I have not done this myself since it did not fit my own needs (and I'm slightly lazy)
Enjoy:
*/
function imageTextWrapped(&$img, $x, $y, $width, $font, $color, $text, $textSize, $align="l") {
$y += $fontSize; //Correct place for the fonts.
$text = str_replace ("\\r", '', $text); //Remove windows line-breaks
$srcLines = split ("\\n", $text); //Split text into "lines"
$dstLines = Array(); // The destination lines array.
foreach ($srcLines as $currentL) {
$line = '';
$words = split (" ", $currentL); //Split line into words.
foreach ($words as $word) {
$dimensions = imagettfbbox($textSize, 0, $font, $line.' '.$word);
$lineWidth = $dimensions[4] - $dimensions[0]; // get the length of this line, if the word is to be included
if ($lineWidth > $width && !empty($line) ) { // check if it is too big if the word was added, if so, then move on.
$dstLines[] = trim($line); //Add the line like it was without spaces.
$line = '';
}
$line .= $word.' ';
}
$dstLines[] =  trim($line); //Add the line when the line ends.
}
//Calculate lineheight by common characters.
$dimensions = imagettfbbox($textSize, 0, $font, "MXQJPmxqjp123"); //use a custom string to get a fixed height.
$lineHeight = $dimensions[1] - $dimensions[5]; // get the heightof this line
$align = strtolower(substr($align,0,1)); //Takes the first letter and converts to lower string. Support for Left, left and l etc.
foreach ($dstLines as $nr => $line) {
if ($align != "l") {
$dimensions = imagettfbbox($textSize, 0, $font, $line);
$lineWidth = $dimensions[4] - $dimensions[0]; // get the length of this line
if ($align == "r") { //If the align is Right
$locX = $x + $width - $lineWidth;
} else { //If the align is Center
$locX = $x + ($width/2) - ($lineWidth/2);
}
} else { //if the align is Left
$locX = $x;
}
$locY = $y + ($nr * $lineHeight);
//Print the line.
imagettftext($img, $textSize, 0, $locX, $locY, $color, $font, $line);
} 
}
?>
<?php
/*
Nimja (13-Jan-2006 04:32)

My previous function had 2 bugs which are now fixed:
* Align is now pixel perfect, no longer dependant on the first character. Solved by putting a space " " in front of every rendered line, making the basepoint at the exact same place every time.
* X Y Coordinates are no longer the base-point, but the perfect top/left coordinates. This made my life designing a LOT easier.
Features:
* Newline support! (both Windows and Linux)
* Paragraph support, no newlines are ignored, not even empty ones. So empty lines are properly supported.
* True top/left x/y coordinates instead of TTF basepoint.(at least as close as possible).
* Align function for Left, Center and Right
* Support for words that are longer then the supported width.
*/
//A function for pixel precise text Wrapping
function imageTextWrapped(&$img, $x, $y, $width, $font, $color, $text, $textSize, $align="l") {
//Recalculate X and Y to have the proper top/left coordinates instead of TTF base-point
$y += $textSize;
$dimensions = imagettfbbox($textSize, 0, $font, " "); //use a custom string to get a fixed height.
$x -= $dimensions[4]-$dimensions[0];
$text = str_replace ("\r", '', $text); //Remove windows line-breaks
$srcLines = split ("\n", $text); //Split text into "lines"
$dstLines = Array(); // The destination lines array.
foreach ($srcLines as $currentL) {
$line = '';
$words = split (" ", $currentL); //Split line into words.
foreach ($words as $word) {
$dimensions = imagettfbbox($textSize, 0, $font, $line.$word);
$lineWidth = $dimensions[4] - $dimensions[0]; // get the length of this line, if the word is to be included
if ($lineWidth > $width && !empty($line) ) { // check if it is too big if the word was added, if so, then move on.
$dstLines[] = ' '.trim($line); //Add the line like it was without spaces.
$line = '';
}
$line .= $word.' ';
}
$dstLines[] =  ' '.trim($line); //Add the line when the line ends.
}
//Calculate lineheight by common characters.
$dimensions = imagettfbbox($textSize, 0, $font, "MXQJPmxqjp123"); //use a custom string to get a fixed height.
$lineHeight = $dimensions[1] - $dimensions[5]; // get the heightof this line
$align = strtolower(substr($align,0,1)); //Takes the first letter and converts to lower string. Support for Left, left and l etc.
foreach ($dstLines as $nr => $line) {
if ($align != "l") {
$dimensions = imagettfbbox($textSize, 0, $font, $line);
$lineWidth = $dimensions[4] - $dimensions[0]; // get the length of this line
if ($align == "r") { //If the align is Right
$locX = $x + $width - $lineWidth;
} else { //If the align is Center
$locX = $x + ($width/2) - ($lineWidth/2);
}
} else { //if the align is Left
$locX = $x;
}
$locY = $y + ($nr * $lineHeight);
//Print the line.
imagettftext($img, $textSize, 0, $locX, $locY, $color, $font, $line);
} 
}
?>

