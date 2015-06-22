<?php

	$servername = "localhost";
	$username = "root";//Nicht vorhanden
	$password = "";//Nicht vorhanden
	$dbname = "myBooks";
	//$autor_id, $genre_id, $art_id;
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

    if ($_GET["art"] == "horror") {
        $art = "Horror";
        $bezeichnung = "horrordata";

    } else if ($_GET["art"] == "roman") {
        $art = "Roman";
        $bezeichnung = "romandata";
    }

    $sql = "SELECT * FROM Buch  ";
    $result = mysqli_query($conn, $sql);


    echo "{  
    		\"$bezeichnung\": [";

	if (mysqli_num_rows($result) > 0) {
    // output data of each row
  		while($row = mysqli_fetch_assoc($result)) {
	        echo 	"\"autor\": \"Stephen King\",
					\"titel\": \"". $row["titel"]."\",
					\"kapitel\": ". $row["kapitel"].",
					\"buchart\": \"Taschenbuch\",
					\"ISBN\": ". $row["isbn"].",
					\"erscheinungsjahr\": ". $row["jahr"].",
					\"auflage\": ". $row["auflage"]."
					}";

		}

	}	

	echo "] }";

?>