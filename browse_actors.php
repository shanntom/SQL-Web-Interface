<html>

<?php 
readfile('header.html');
?>


<body>
<h1>Actor/Actress Information</h1>

<?php
  $db_connection = mysql_connect("localhost", "cs143", "");
  if (!$db_connection) {
      die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("CS143", $db_connection);

  if($_GET["aid"]){
  	// Get actor ID to establish actor information page
  	$aid = $_GET["aid"];

  	// Run queries for the movies the Actor/Actress is in and find his/her name
  	$query = "SELECT * FROM MovieActor WHERE aid=\"$aid\"";
  	$actor_query = "SELECT last, first FROM Actor WHERE id=\"$aid\"";
  	// echo $query;
  	// echo "<br>";  	
  	// echo $actor_query;
  	// echo "<br>";
    $result = mysql_query($query, $db_connection);
    $resultActor = mysql_query($actor_query, $db_connection);
    $resultMovie = "N/A";
    if (!$result || !$resultActor) {
        echo "Could not successfully run query ($result) or ($resultActor) from DB: " . mysql_error();
    }

    if (mysql_num_rows($result) == 0 || mysql_num_rows($resultActor) == 0) {
        echo "No information found.";
    }
    else{
      $actor_name = mysql_fetch_row($resultActor);
      echo "<h2>$actor_name[1] $actor_name[0] </h2>";
      $first = true;
      while($row = mysql_fetch_row($result)) {
		if($first){
			$first = false;
			echo '<table><tr>';
			echo '<th>Movie</th>';
			echo '<th>Role</th>';
			echo '</tr>';
		}

		$mid = $row[0];
		$movie_query = "SELECT title FROM Movie WHERE id=\"$mid\"";
		// echo $movie_query;	
		$resultMovie = mysql_query($movie_query, $db_connection);
		if (!$resultMovie) {
        	echo "Could not successfully run query ($resultMovie) from DB: " . mysql_error();
	    }

	    if (mysql_num_rows($resultMovie) == 0) {
	        echo "N/A";
   		}

   		$movie_name = mysql_fetch_row($resultMovie);
		echo '<tr>';
		echo '<td><a href=browse_movies.php?mid=' .$mid. '>' .$movie_name[0].'</td>';
		echo '<td>'.$row[2].'</td>';
		echo '</tr>';
	}
	echo '</table>';
  }
  }




mysql_close($db_connection);

?>

</body>
</html>