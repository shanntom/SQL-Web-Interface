<html>
<?php 
readfile('header.html');
?>


<body>
<h1>Add a Moive/Actor Relation</h1>

<form action="" method="GET">

<p>Movie Title
<SELECT NAME="mid" SIZE=1><option value="NULL"></option>
<?php 
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
	while($row = mysql_fetch_row($queryResult)) {
		echo sprintf($optionFormat, $row[0], $row[1], $row[2]);
	}
	mysql_close($db_connection);
?>
</SELECT></p>

<p>Actor
<SELECT NAME="aid" SIZE=1><option value="NULL"></option>
<?php 
	$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
	    	die('Could not connect: ' . mysql_error());
		}
	mysql_select_db("CS143", $db_connection);
	$query = "SELECT id, first, last, dob FROM Actor ORDER BY last";
	$queryResult = mysql_query($query, $db_connection);
		if (!$queryResult) {
		    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
		    exit;
		}

	$optionFormat = "<option value=%d>%s %s (%s) </option>";
	while($row = mysql_fetch_row($queryResult)) {
		echo sprintf($optionFormat, $row[0], $row[1], $row[2], $row[3]);
	}
	mysql_close($db_connection);
?>
</SELECT></p>

<p>Role<input type="text" name="role" size=30 maxlength=50></p>

<br>
<input type="submit" name="Submit"/>
</form>



<?php 

if (isset($_GET['Submit'])) {

	//test if valid input
	$validInput = true; 
	if($_GET["mid"] == "NULL") {
		echo "You must select a movie</br>";
		$validInput = false;
	}
	if($_GET["aid"] == "NULL") {
		echo "You must select an actor</br>";
		$validInput = false;
	}
	if($_GET["role"] == "") {
		echo "You must input a role</br>";
		$validInput = false;
	} 
	if (! $validInput) {
		echo "Invaild input, exiting </br>";
		exit;
	}

	// open connection
	$db_connection = mysql_connect("localhost", "cs143", "");
		if (!$db_connection) {
	    	die('Could not connect: ' . mysql_error());
		}
	mysql_select_db("CS143", $db_connection);


	//format query
	$queryFormat = "INSERT INTO MovieActor VALUES (%d, %d, '%s')";
	$query = sprintf($queryFormat, 
		$_GET["mid"], 
		$_GET["aid"],
		mysql_real_escape_string($_GET["role"], $db_connection));


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