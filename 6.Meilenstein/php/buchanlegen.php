<?php
	/* Diese Datei schreibt in  die Datenbank die Bücher ,User und fasst die favoriten zusammen.


	*/


	//Hinweis: Da eine nicht gerechtfertigte Fehlermeldung ausgeworfen wird, werden alle Fehlermeldungen vom Typ Notice
	//an dieser Stelle aus optischen Gründen deaktiviert.
	//Notice: Undefined index: filmfavorit in /Applications/XAMPP/xamppfiles/htdocs/m/php/buchanlegen.php on line 111
	error_reporting(E_ALL & ~E_NOTICE);


	// Standardtext - tritt ein Validierungsfehler auf, wird dieser geändert
	$returntext = "Vielen Dank für Ihre Eingabe. Daten wurden uebermittelt!";
	//Für die Validierung:
	$buchstaben = "/[a-zA-Z\s]+$/";
	$zahlen = "/^[0-9]+$/";

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
		
	// TABELLE Buch WIRD HIER BEARBEITET---------------
	
	$sql = "SELECT isbn FROM Buch WHERE isbn = ".$_GET["isbn"]." ; ";
	$row_buch = mysqli_fetch_assoc( mysqli_query($conn, $sql) ); 

	//Buch anlegen oder buch vorhanden
	if($row_buch["isbn"] == ""){
		//buch anlegen
		$autor_id = readAutor($conn, $_GET["autor"]);
		
		$result =  mysqli_fetch_assoc( mysqli_query($conn, "SELECT id FROM Art WHERE art = '".$_GET["art"]."';")); 
		$art_id = $result["id"];
		
		$result =  mysqli_fetch_assoc( mysqli_query($conn, "SELECT id FROM Genre WHERE genre = '".$_GET["genre"]."';")); 	
		$genre_id = $result["id"]; 

		$isbn = $_GET["isbn"];
		$titel = $_GET["titel"];
		$jahr = $_GET["jahr"];
	

		// Insert nur falls Bedingungen erfüllt (= Validierung)
		if(strlen($isbn)==13 && preg_match($zahlen,$isbn) && preg_match($buchstaben,$titel) && strlen($jahr)==4 && $jahr<2016)
		{

		$sql = "INSERT INTO Buch (isbn, autor_id, titel, kapitel, art_id, jahr, auflage, genre_id)VALUES (
				".$_GET["isbn"].",
				$autor_id, 
				'".$_GET["titel"]."',
				'".$_GET["kapitel"]."',
				$art_id, 
				'".$_GET["jahr"]."',
				'".$_GET["auflage"]."', 
				$genre_id );";
		mysqli_query($conn, $sql);		
		}
		else{
			$returntext = "Es ist ein Fehler aufgetreten(Buch)! Daten teilweise nicht geschrieben";
		}
	}
	
	// TABELLE User WIRD HIER BEARBEITET---------------
	
	$sql = "SELECT id FROM User 
		WHERE vorname = '".$_GET["vorname"]."' 
		AND	  nachname = '".$_GET["name"]."'";
		 
	$row_user = mysqli_fetch_assoc( mysqli_query($conn, $sql) ); 
	$vorname = $_GET["vorname"];
	$nachname = $_GET["name"];
	//User anlegen oder User vorhanden
	if($row_user["id"] == ""){
		//User anlegen

//Überprüfung, ob zu schreibende Daten Valide sind - nur falls ja wird Datensatz geschrieben
		if(preg_match($buchstaben,$vorname) && preg_match($buchstaben,$nachname)){
		$sql_user = "INSERT INTO User (vorname,nachname)VALUES
				('".$_GET["vorname"]."' , '".$_GET["name"]."' )";
		mysqli_query($conn, $sql_user);		
		$row_user = mysqli_fetch_assoc( mysqli_query($conn, $sql) );	
		}
		else {
			$returntext ="Es ist ein Fehler aufgetreten (User)! Daten teilweise nicht geschrieben";
		}	
	}

	//Favoriten Eintrag in die Datenbank-----------------------------
		
	$sql_user = "SELECT id FROM User 
					WHERE vorname = '".$_GET["vorname"]."' 
					AND	  nachname = '".$_GET["name"]."'";
	$row_user = mysqli_fetch_assoc(mysqli_query($conn, $sql_user));	
	
	$sql_buch = "SELECT isbn FROM Buch WHERE isbn = ".$_GET["isbn"]." ; ";
	$row_buch = mysqli_fetch_assoc(mysqli_query($conn, $sql_buch));
	
	//Überprüft, ob eintrag schon vorhanden ist
	$sql_favorit = "SELECT * FROM favoriten WHERE 
					user_id =  ".$row_user["id"]." AND buch_isbn = ".$row_buch["isbn"].";";

	$row_fav =  mysqli_fetch_assoc(mysqli_query($conn, $sql_favorit));	

	if ($_GET["filmfavorit"]== null)
			$favo = "off";		
	else $favo = "on";
	if($row_fav["fav_id"] == ""){
		$sql_favorit = " INSERT INTO favoriten (user_id, buch_isbn, favorit) VALUES
					( ".$row_user["id"].", ".$row_buch["isbn"].",'".$favo."' );";
		mysqli_query($conn, $sql_favorit);	
	}

	
	
	mysqli_close($conn);
		
	echo $returntext ;	// Erfolgsmeldung, bzw. Hinweis auf Fehler
		
	//liest einen Datensatz raus
	function readAutor($conn, $name){
		
		$sql = "SELECT id FROM Autor WHERE name = '".$name."'; ";
		$row = mysqli_fetch_assoc(mysqli_query($conn, $sql)); 
		
		if($row["id"] == ""){
			
			$sqlautor = "INSERT INTO Autor (name) VALUES	
						('$name'); ";
			mysqli_query($conn, $sqlautor);			//Autor wird in die Tabelle geschrieben
			$result =  mysqli_query($conn, $sql);	
			$row = mysqli_fetch_assoc($result); 		//Die id vom Autor wird geholt.
			
		}		
		
		return $row["id"];		
	}

	

?>
