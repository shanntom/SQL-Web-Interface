<html>

<?php 
readfile('header.html');
?>

<body>
<h1>Search Page</h1>
<h3>Search for an actor or movie</h3>

<form action="" method="GET">
<input type="text" name="search" style="width: 70%">
<br>
<input type="submit" />
</form>

<?php
  $db_connection = mysql_connect("localhost", "cs143", "");
  if (!$db_connection) {
      die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("CS143", $db_connection);
  session_start();  

  if($_GET["search"]){
    $user_input = $_GET["search"];
    $valid_actor = true; // Check if the search is valid

      // // Check if one or more words
      if(!strpos($user_input, " ")){
        $queryActor = "SELECT * FROM Actor WHERE last LIKE \"%$user_input%\" OR first LIKE \"%$user_input%\"";
        $queryMovie = "SELECT * FROM Movie WHERE title LIKE \"%$user_input%\"";
        echo $queryMovie;
        // Movie needs to match the if the word exists in the title
        // $queryMovie = "SELECT * FROM Movie WHERE" . "title=" $user_input .;
      }
      else{
        $words = explode(" ", $user_input); 
        if(sizeof($words) == 2){
          $queryActor = "SELECT * FROM Actor WHERE last LIKE \"%$words[0]%\" AND first LIKE \"%$words[1]%\"
                         OR last LIKE \"%$words[1]%\" AND first LIKE \"%$words[0]%\"";
        }
        else
          $valid_actor = false;

        $queryMovie = "SELECT * FROM Movie WHERE title LIKE \"%$words[0]%\"";
        for($i = 1; $i < sizeof($words); $i++){
          $queryMovie .= " AND title LIKE \"%$words[$i]%\"";
        }
        echo $queryMovie . "<br>";
      }
      
      // Actor/Actress search results
      echo "<h3>Actors/Actresses</h3>";
      if($valid_actor){
        $resultActor = mysql_query($queryActor, $db_connection);
        if (!$resultActor) {
            echo "Could not successfully run query ($resultActor) from DB: " . mysql_error();
        }

        if (mysql_num_rows($resultActor) == 0) {
            echo "No actor or actress found.";
        }
        else{
          $first = true;
          while($row = mysql_fetch_row($resultActor)) {
            $len = count($row);
            if($first){
              $first = false;
              echo '<table><tr>';
              echo '<th>ID</th>';
              echo '<th>Name</th>';
              echo '<th>Date of Birth</th>';
              echo '</tr>';
            }

            echo '<tr>';
            echo '<td>' . $row[0] . '</td>';
            echo '<td><a href=browse_actors.php?aid=' . $row[0] . '>'  . $row[2] . ' ' . $row[1] . '</a></td>';
            echo '<td>' . $row[4] . '</td>';
            echo '</tr>';

          }
          echo '</table>';
        }
      }
      else{
        echo "No actor or actress found.";
      }

      // Movie search results
      echo "<h3>Movies</h3>";
      $resultMovie = mysql_query($queryMovie, $db_connection);
      if (!$resultMovie) {
          echo "Could not successfully run query ($resultMovie) from DB: " . mysql_error();
          exit;
      }

      if (mysql_num_rows($resultMovie) == 0) {
          echo "No movies found.";
          exit;
      }

      $first = true;
      while($row = mysql_fetch_row($resultMovie)) {
        $len = count($row);
        if($first){
          $first = false;
          echo '<table><tr>';
          echo '<th>ID</th>';
          echo '<th>Title</th>';
          echo '<th>Year</th>';
          echo '</tr>';
        }

        echo '<tr>';
        echo '<td>' . $row[0] . '</td>';
        echo '<td><a href=browse_movies.php?mid=' . $row[0] . '>' . $row[1] . '</td>';
        echo '<td>' . $row[2] . '</td>';
        echo '</tr>';
      }

      echo '</table>';
  }

  mysql_close($db_connection);
  session_unset();
  session_destroy();

?>

</body>
</html>
