<html>

<?php 
readfile('header.html');
?>


<body>
<h1>Movie Information</h1>

<?php
  $db_connection = mysql_connect("localhost", "cs143", "");
  if (!$db_connection) {
      die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("CS143", $db_connection);

  if($_GET["mid"]){
    // Get actor ID to establish actor information page
    $mid = $_GET["mid"];

    // Run Movie query for title, MPAA rating and production company 
    // Run Movie genre query for genre
    // Run MovieDirector, Director query for list of directors (did)
    // Run MovieActor query for list of actors (aid) and their roles
    // Run Actor query for names of the list of actors 
    //, d.last, d.first, g.genre , Director d, MovieGenre g, MovieDirector md
    $query1 = "SELECT m.title, m.rating, m.company FROM Movie m WHERE m.id=\"$mid\"";
    $query2 = "SELECT genre FROM MovieGenre WHERE mid=\"$mid\"";
    $query3 = "SELECT d.last, d.first FROM Director d, MovieDirector md WHERE md.mid=\"$mid\" and md.did=d.id";
    $actor_query = "SELECT id, last, first, role FROM Actor a, MovieActor ma WHERE ma.mid=\"$mid\" and ma.aid=a.id";
    // echo $query3;
    // echo "<br>";    
    // echo $actor_query;
    // echo "<br>";
    $result1 = mysql_query($query1, $db_connection);
    $result2 = mysql_query($query2, $db_connection);
    $result3 = mysql_query($query3, $db_connection);    
    $resultActor = mysql_query($actor_query, $db_connection);
    if (!$result1 || !$result2 || !$result3 || !$resultActor) {
        echo "Could not successfully run query ($result1 ) or ($resultActor) from DB: " . mysql_error();
    }
    if (mysql_num_rows($result1) == 0){// || mysql_num_rows($resultActor) == 0) {
        echo "No movie found.";
    }
    else{
        // Print Movie information
        $row = mysql_fetch_row($result1);
        echo '<h2>' . $row[0] . '</h2>';
        echo 'MPAA Rating: '.$row[1].'<br>';
        echo 'Production Company: '.$row[2].'<br>';

        // Print Director information
        echo 'Director: ';
        if (mysql_num_rows($result3) == 0){// || mysql_num_rows($resultActor) == 0) {
            echo " N/A<br>";
        }
        else{
            $director_name = mysql_fetch_row($result3);
            echo $director_name[1].' '.$director_name[0].'<br>';                
        }

        // Print Genre information
        echo 'Genre:';
        if (mysql_num_rows($result2) == 0){// || mysql_num_rows($resultActor) == 0) {
            echo " N/A<br>";
        }
        else{
            while($genre = mysql_fetch_row($result2)){
                echo ' '.$genre[0];
            }
        }

        // Print Actors/Actresses information
        echo '<table><tr>';
        echo '<th>Actors/Actresses</th>';
        echo '<th>Role</th>';
        echo '</tr>';
        if (mysql_num_rows($resultActor) == 0) {
            echo "<tr><td>No actors/actresses found.</td>
                      <td>N/A</td>
                  </tr>";
        }
        else{
            while($actor_info = mysql_fetch_row($resultActor)){
                echo '<tr>';
                echo '<td><a href=browse_actors.php?aid=' . $actor_info[0] . '>'  . $actor_info[2] . ' ' . $actor_info[1] 
                      . '</a></td>';
                echo '<td>' . $actor_info[3] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }


        // execute query to find ave rating
        $aveQuery = sprintf("SELECT ROUND(AVG(rating),2) from Review where mid=%d", $mid);
        $queryResult = mysql_query($aveQuery, $db_connection);
            if (!$queryResult) {
                echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
                exit;
            }
        $ave = mysql_fetch_row($queryResult)[0];
        echo "<h3>Average User Rating</h3>";
        if ($ave == NULL)  {
            echo "There are no user ratings for this movie <br>";
        }
        else {
            echo "<h4>".$ave."</h4>";
        }

        // format and execute query for comments
        $commentQuery = sprintf("SELECT name, rating, comment, time FROM Review WHERE mid=%d", $mid);
        $queryResult = mysql_query($commentQuery, $db_connection);
            if (!$queryResult) {
                echo "Could not successfully run query ".$queryResult."from DB: ".mysql_error();
                exit;
            }

        // display comments
        $commentFormat = "<strong>%s (Rating: %d/100) Says: </strong>%s<br> At %s<br><br>";
        $firstRow = true;
        echo "<h3>User Reviews</h3>";
        while ($row = mysql_fetch_row($queryResult)) {
            $firstRow = false;
            echo sprintf($commentFormat, $row[0], $row[1], $row[2], $row[3]);
        }
        if ($firstRow) {
            echo "There are no reviews for this moive<br>";
        }

        // link to add a comment
        $commentLinkFormat = "<a href='inputComment.php?mid=%d'>Leave a comment for this movie! </a>";
        $commentLink = sprintf($commentLinkFormat, $mid);
        echo $commentLink;

    }

  }



mysql_close($db_connection);

?>

</body>
</html>