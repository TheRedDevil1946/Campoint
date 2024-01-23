<?php

require_once ('config.php');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("Verbindungsfehler: " . $mysqli->connect_error);
}

$forename = $_POST['forename'];
$surname = $_POST['surname'];
$phonenumber = $_POST['phonenumber'];

$mysqli->query('INSERT INTO telefonbuch (vorname, nachname, telefonnummer) VALUES ("' . $forename . '", "' . $surname . '", "' . $phonenumber . '")');

$mysqli->close();

header('Location: ' . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));
die();