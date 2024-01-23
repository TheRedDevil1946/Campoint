<!doctype html>
<html lang="en">
  <head>


  </head>

  <body class="index">
    <h2>Telefonbucheintrag erstellen</h2>
    <form action="createUser.php" method="POST" name="createUser">
      <label>Vorname:</label><input type="text" name="forename" required></br>
      <label>Nachname:</label><input type="text" name="surname" required></br>
      <label>Telefonnummer:</label><input type="number" name="phonenumber" required></br>
      <button type="submit">Speichern</button>
    </form>
    <h3>Im Telefonbuch suchen</h3>
    <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
      <input type="text" name="searchTerm" required>
      <button type="submit">Suchen</button>
    </form>
  </body>
</html>

<?php

require_once ('config.php');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Verbindungsfehler: " . $mysqli->connect_error);
}

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

$t9Mapping = [
  '1' => '',
  '2' => 'abc',
  '3' => 'def',
  '4' => 'ghi',
  '5' => 'jkl',
  '6' => 'mno',
  '7' => 'pqrs',
  '8' => 'tuv',
  '9' => 'wxyz',
  '0' => ' ',
];

$searchTerm = str_split($searchTerm);
$possibleWords = [''];

foreach ($searchTerm as $digit) {
  $digitOptions = str_split($t9Mapping[$digit]);
  $newPossibleWords = [];

  foreach ($possibleWords as $word) {
      foreach ($digitOptions as $option) {
          $newPossibleWords[] = $word . $option;
      }
  }

  $possibleWords = $newPossibleWords;
}

$likeClause = implode(" OR vorname LIKE ", array_map(function ($word) {
  return "'%$word%'";
}, $possibleWords));


if ($searchTerm !== '') {
  $sql = "SELECT * FROM telefonbuch WHERE $likeClause";
  $data = $mysqli->query($sql);

  // $data = $mysqli->query("SELECT * FROM telefonbuch WHERE vorname LIKE '%$searchTerm%'");
  
  if ($data->num_rows > 0) {
      echo "<ul>";
      while ($row = $data->fetch_assoc()) {
          echo "<li>{$row['vorname']}</li>";
      }
      echo "</ul>";
  } else {
      echo "<p>Keine Ergebnisse gefunden.</p>";
  }
  
}