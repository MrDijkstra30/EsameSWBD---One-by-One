<?php

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "piattaformalingue";

// Connessione al database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verifica connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
