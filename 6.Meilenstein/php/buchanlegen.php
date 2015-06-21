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
		
	// TABELLE Buch WIRD HIER BEARBEITET---------------
	
	$sql = "SELECT isbn FROM Buch WHERE isbn = ".$_GET["isbn"]." ; ";
	 
	$row = mysqli_fetch_assoc( mysqli_query($conn, $sql) ); 

	
	//Buch anlegen oder buch vorhanden
	if($row["isbn"] == ""){
		//buch anlegen
		$autor_id = readAutor($conn, $_GET["autor"]);
		
		$result =  mysqli_fetch_assoc( mysqli_query($conn, "SELECT id FROM Art WHERE art = '".$_GET["art"]."';")); 
		$art_id = $result["id"];
		
		$result =  mysqli_fetch_assoc( mysqli_query($conn, "SELECT id FROM Genre WHERE genre = '".$_GET["genre"]."';")); 	
		$genre_id = $result["id"]; 
		
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
	
	// TABELLE User WIRD HIER BEARBEITET---------------
	
	$sql = "SELECT id FROM User 
		WHERE vorname = '".$_GET["vorname"]."' 
		AND	  nachname = '".$_GET["name"]."'";
		 
	$row = mysqli_fetch_assoc( mysqli_query($conn, $sql) ); 
	
	//User anlegen oder User vorhanden
	if($row["id"] == ""){
		//User anlegen
		$sqluser = "INSERT INTO User (vorname,nachname)VALUES
				('".$_GET["vorname"]."' , '".$_GET["name"]."' )";
		mysqli_query($conn, $sqluser);		
		$row = mysqli_fetch_assoc( mysqli_query($conn, $sql) );		
	}

	//Favoriten Behandlung-----------------------------
	
	
	
	
	
	
	mysqli_close($conn);
		
		
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

	


    echo 'Daten wurden geschrieben ' ;

?>
