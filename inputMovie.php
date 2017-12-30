<html>
<?php 
readfile('header.html');
?>

<body>
<h1>Add Movie</h1>

<form action="" method="GET">

<p>Title<input type="text" name="title" size=30 maxlength=100></p>
<p>Year <input type="text" name="year" size=30 maxlength=4></p>
<p>MPAA Rating <SELECT name="rating" size=1> 
<option value="NULL"></option>
<option value="G">G</option>
<option value="NC-17">NC-17</option>
<option value="PG">PG</option>
<option value="PG-13">PG-13</option>
<option value="R">R</option>
<option value="surrendere">surrendere</option>
</SELECT></p>
<p>Production Company <input type="text" name="company" size=30 maxlength=50></p>
<p> Genre </br>
<SELECT NAME="genre[]" multiple SIZE=7>
<OPTION>Action<OPTION>Adult<OPTION>Adventure<OPTION>Animation<OPTION>Comedy
<OPTION>Crime<OPTION>Documentary<OPTION>Drama<OPTION>Family<OPTION>Fantasy
<OPTION>Horror<OPTION>Musical<OPTION>Mystery<OPTION>Romance<OPTION>Sad<OPTION>Sci-Fi
<OPTION>Short<OPTION>Thriller<OPTION>War<OPTION>Western
</SELECT> </p>

<br>
<input type="submit" name="Submit"/>
</form>


<?php 
function getMaxId($table, $connection) {
	$idQuery = "SELECT max(id) from ".$table;
	$idResult = mysql_query($idQuery, $connection) ;
	if (!$idResult) {
	    echo "Could not successfully run query ".$idQuery."from DB: ".mysql_error();
	    exit;
	}
	if (mysql_num_rows($idResult) == 0) {
	    echo "No rows found.";
	    exit;
	}
	$id = mysql_fetch_row($idResult)[0];
	return $id;
}
function insertGenre($mid, $genre, $connection) {
	$queryFormat = "INSERT INTO %s VALUES (%d, '%s')";
	$query = sprintf($queryFormat, 
		"MovieGenre", 
		$mid, 
		$genre);
	$queryResult = mysql_query($query, $connection);
		if (!$queryResult) {
		    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
		    exit;
		}
}



if (isset($_GET['Submit'])) {

	// test if valid inputs
	$validInput = true; 
	if($_GET["title"] == "") {
		echo "You must input a title</br>";
		$validInput = false;
	} 
	if($_GET["year"] == "") {
		echo "You must input a year </br>";
		$validInput = false;
	}
	if (preg_match("/^[0-9]{4}$/", $_GET["year"]) == false) {
		echo "You must input a valid year </br>";
		$validInput = false;
	} 
	if($_GET["rating"] == "NULL") {
		echo "You must input a rating </br>";
		$validInput = false;
	}
	if($_GET["company"] == "") {
		echo "You must input a company</br>";
		$validInput = false;
	}
	if(sizeof($_GET["genre"]) == 0) {
		echo "You must select at least one genre</br>";
		$validInput = false;
	}

	if (! $validInput) {
		echo "Invaild input, exiting </br>";
		exit;
	}

	//open connection
	$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
	    	die('Could not connect: ' . mysql_error());
		}
	mysql_select_db("CS143", $db_connection);

	//get maxId for movie
	$newId = getMaxId("MaxMovieID", $db_connection) + 1;

	//format query into Movie
	$queryFormat = "INSERT INTO %s VALUES (%d, '%s', %s, '%s', '%s')";
	$query = sprintf($queryFormat, 
		"Movie", 
		$newId, 
		mysql_real_escape_string($_GET["title"], $db_connection), 
		$_GET["year"], 
		mysql_real_escape_string($_GET["rating"], $db_connection), 
		mysql_real_escape_string($_GET["company"], $db_connection));


	// execute Movie query
	$queryResult = mysql_query($query, $db_connection);
		if (!$queryResult) {
		    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
		    exit;
		}
		else {  // update maxID
			$updateQueryFormat = "UPDATE MaxMovieID SET id=%d";
			$updateQuery = sprintf($updateQueryFormat, $newId);
			$queryResult = mysql_query($updateQuery, $db_connection);
			if (!$queryResult) {
			    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
			    exit;
			}
		}

	// update MovieGenre
	foreach ($_GET['genre'] as $selectedGenre){
	    insertGenre($newId, $selectedGenre, $db_connection);
	}
	
	echo "Success!";
	mysql_close($db_connection);
}


?>



</body>
</html>

