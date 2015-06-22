<?php
	/* Diese Datei sucht je nach auswahl alle Roman oder Horror Bücher und schreibt
		sie in Json format auf. 



	*/
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
        $genre = "Horror";
        $bezeichnung = "horrordata";

    } else if ($_GET["art"] == "roman") {
        $genre = "Roman";
        $bezeichnung = "romandata";
    }

	$sql = "SELECT * FROM Buch WHERE  genre_id = (SELECT id FROM Genre WHERE genre = '".$genre."')";  
    $result = mysqli_query($conn, $sql);

   
	$json = "{  
    		\"$bezeichnung\": [";
	if (mysqli_num_rows($result) > 0) {
  		while($row = mysqli_fetch_assoc($result)) {
  			$sql_autor = "SELECT name FROM Autor WHERE id = ". $row["autor_id"]." ; ";
  			$sql_art = "SELECT art FROM Art WHERE id = ". $row["art_id"]."; ";
			$row_autor = mysqli_fetch_assoc( mysqli_query($conn, $sql_autor) );
			$row_art = mysqli_fetch_assoc( mysqli_query($conn, $sql_art) );	
			$json.=	"{
					\"autor\": \"".$row_autor["name"]."\",
					\"titel\": \"". $row["titel"]."\",
					\"kapitel\": ". $row["kapitel"].",
					\"buchart\": \"".$row_art["art"]."\",
					\"ISBN\": ".$row["isbn"].",
					\"erscheinungsjahr\": ". $row["jahr"].",
					\"auflage\": ". $row["auflage"]."
					},";
		}
		$json = substr($json, 0, -1); //schneidet das letzte zeichen ab
	}	
	
	echo $json."] }";

	mysqli_close($conn);
	?>