<html>
<?php 
readfile('header.html');
?>


<body>

<h1>Add Actor or Director</h1>

<form action="" method="GET">

<p>Person's Role: <br> Actor<input type="radio" name="table" value="Actor">
Director <input type="radio" name="table" value="Director"></p>

<p>First Name <input type="text" name="first" size=20 maxlength=20></p>
<p>Last Name <input type="text" name="last" size=20 maxlength=20></p>

<p>Sex: <br> Female<input type="radio" name="sex" value="Female">
Male: <input type="radio" name="sex" value="Male"></p>

<p>Date of Birth <input type="text" name="dob" size=10 maxlength=20> <br>
<font size="-1"> Format: YYYY-MM-DD   e.g.: 1997-09-01</font></p>

<p>Date of Death <input type="text" name="dod" size=10 maxlength=20> <br>
<font size="-1"> Format: YYYY-MM-DD   e.g.: 1997-09-01 <br> Leave blank if alive</font>
</p>

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

	function checkDateFormat($date) {
		if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$date)) {
		    return true;
		} else 
		    return false;
	}

	if (isset($_GET['Submit'])) {

		// test if valid inputs
		$validInput = true; 
		$formattedDod = "NULL";
		if($_GET["table"] == "") {
			echo "You must choose actor or director</br>";
			$validInput = false;
		} 
		if($_GET["first"] == "") {
			echo "You must input a first name </br>";
			$validInput = false;
		}
		if($_GET["last"] == "") {
			echo "You must input a last name </br>";
			$validInput = false;
		}
		if($_GET["sex"] == "" &&  $_GET["table"] == "Actor") {
			echo "You must input a sex </br>";
			$validInput = false;
		}
		if($_GET["dob"] == "") {
			echo "You must input a date of birth </br>";
			$validInput = false;
		} 
		else if (!checkDateFormat($_GET["dob"])) {
			echo "Invalid date of birth format </br>";
			$validInput = false;
		}
		if($_GET["dod"] != "") {
			if (!checkDateFormat($_GET["dod"])) {
				echo "Invalid date of death format </br>";
				$validInput = false;
			}
			else {
				$formattedDod = "'".$_GET["dod"]."'";
			}
		}


		if (! $validInput) {
			echo "Invaild input, exiting. </br>";
			exit;
		}


		// setup connection to db
		$db_connection = mysql_connect("localhost", "cs143", "");
			if (!$db_connection) {
		    	die('Could not connect: ' . mysql_error());
			}
		mysql_select_db("CS143", $db_connection);

		//get maxId for person
		$newId = getMaxId("MaxPersonID", $db_connection) + 1;

		$query;
		// format querries for Actor and Director
		if ($_GET["table"] == "Actor") {
			$queryFormat = "INSERT INTO %s VALUES (%d, '%s', '%s', '%s', '%s', %s)";
			$query = sprintf($queryFormat, 
			$_GET["table"], 
			$newId, 
			mysql_real_escape_string($_GET["last"], $db_connection), 
			mysql_real_escape_string($_GET["first"], $db_connection), 
			mysql_real_escape_string($_GET["sex"], $db_connection), 
			$_GET["dob"], 
			$formattedDod);}
		else {
			$queryFormat = "INSERT INTO %s VALUES (%d, '%s', '%s', '%s', %s)";
			$query = sprintf($queryFormat, 
			$_GET["table"], 
			$newId, 
			mysql_real_escape_string($_GET["last"], $db_connection), 
			mysql_real_escape_string($_GET["first"], $db_connection), 
			$_GET["dob"], 
			$formattedDod);
		}

		// execute query
		$queryResult = mysql_query($query, $db_connection);
		if (!$queryResult) {
		    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
		    exit;
		}
		else {  // update maxID
			$updateQueryFormat = "UPDATE MaxPersonID SET id=%d";
			$updateQuery = sprintf($updateQueryFormat, $newId);
			$queryResult = mysql_query($updateQuery, $db_connection);
			if (!$queryResult) {
			    echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
			    exit;
			}
		}

		echo "Success!";
		mysql_close($db_connection);
	}

	
?>

</body>
</html>

