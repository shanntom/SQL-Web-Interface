<html>
<?php 
readfile('header.html');
?>

<body>
<h1>Add Review to a Movie</h1>

<form action="" method="GET">

<p>Your Name<input type="text" name="name" size=30 maxlength=20></p>
<p>Rating out of 100<input type="text" name="rating" size=30 maxlength=3></p>
<p>Movie 
<SELECT NAME="mid" SIZE=1><option value="NULL"></option>
<?php 
	$mid = $_GET["mid"];

	// check to see if movie was selected previously as input from link
	$movieSelected = true;
	if ($mid == NULL) {
		$movieSelected = false;
	}

	$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
	    	die('Could not connect: ' . mysql_error());
		}
	mysql_select_db("CS143", $db_connection);
	$query = "SELECT id, title, year FROM Movie ORDER BY title";
	$queryResult = mysql_query($query, $db_connection);
		if (!$queryResult) {
		    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
		    exit;
		}

	$optionFormat = "<option value=%d>%s (%s) </option>";
	if(!$movieSelected) {
		while($row = mysql_fetch_row($queryResult)) {
			echo sprintf($optionFormat, $row[0], $row[1], $row[2]);
		}
	}
	else {
		$selectedFormat = "<option selected value=%d>%s (%s) </option>";
		while($row = mysql_fetch_row($queryResult)) {
			// check to see if this id matches the given mid
			if ($mid == $row[0]) {
				echo sprintf($selectedFormat, $row[0], $row[1], $row[2]);
			} else {
				echo sprintf($optionFormat, $row[0], $row[1], $row[2]);
			}
		}
	}



	mysql_close($db_connection);
?>
</SELECT></p>
<p>Comment</br> <textarea name="comment" rows=15 cols=50 maxlength=500></textarea></p>
<br>
<input type="submit" name="Submit"/>
</form>



<?php 

if (isset($_GET['Submit'])) {

	//test if valid input
	$validInput = true; 
	if($_GET["name"] == "") {
		echo "You must input a name</br>";
		$validInput = false;
	} 
	if($_GET["rating"] == "") {
		echo "You must input a rating</br>";
		$validInput = false;
	}
	else if(!ctype_digit($_GET["rating"])) {
		echo "Rating must be an integer </br>";
		$validInput = false;
	} 
	else if ($_GET["rating"] < 0 || $_GET["rating"] > 100){
		echo "Rating must be between 0 and 100</br>";
		$validInput = false;
	}
	if($_GET["mid"] == "NULL") {
		echo "You must select a movie</br>";
		$validInput = false;
	}

	if (! $validInput) {
		echo "Invaild input, exiting </br>";
		exit;
	}
	$timestamp = date("Y-m-d H:i:s", time());

	// open connection
	$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
	    	die('Could not connect: ' . mysql_error());
		}
	mysql_select_db("CS143", $db_connection);

	//format query
	$queryFormat = "INSERT INTO Review VALUES ('%s', '%s', %d, %d, '%s')";
	$query = sprintf($queryFormat, 
		mysql_real_escape_string($_GET["name"], $db_connection), 
		$timestamp,
		$_GET["mid"], 
		$_GET["rating"],
		mysql_real_escape_string($_GET["comment"], $db_connection));


	// execute Movie query
	$queryResult = mysql_query($query, $db_connection);
		if (!$queryResult) {
		    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
		    exit;
		}

	echo "Success!";
	mysql_close($db_connection);
}

?> 

</body>
</html>