<?php
require_once('../../DBconfiguration/config_db.php');
// Assicurati che $_SESSION['username'] sia impostata e sicura
session_start();
if (!isset($_SESSION['username'])) {
    die(json_encode(array('error' => 'Sessione non valida')));
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $conn->real_escape_string($data['id']);
$sql = "UPDATE corso SET difficolta=1 WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('message' => 'Difficoltà aggiornata'));
} else {
    echo json_encode(array('error' => 'Errore durante l\'inserimento della lezione: ' . $conn->error));
}

$stmt->close();
$conn->close();
