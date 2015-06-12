<?php

    if ($_GET["art"] == "horror") {
        echo file_get_contents('../json/horror_books.json');
    } else if ($_GET["art"] == "roman") {
        echo file_get_contents('../json/roman_books.json');
    }

?>