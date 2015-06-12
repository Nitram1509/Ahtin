<?php
    $file = '../txt/books.txt';
    if (is_writable($file)) {
        $handle = fopen($file, 'a') or die('Konnte nicht geöffnet werden:  ' . $my_file); 
    } 

    $buchtext = $_GET["autor"] . ', ' 
	. $_GET["titel"] . ', '
	. $_GET["kapitel"] . 
	' Kapitel, ' . $_GET["art"] . 
	', ' . $_GET["isbn"] .
	', ' . $_GET["jahr"] . 
	', ' . $_GET["auflage"] .
	'.Auflage;';

    fwrite($handle, $buchtext);

    fclose($handle);
    echo 'Daten wurden geschrieben ' ;

?>