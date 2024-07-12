<?php
require_once('../../DBconfiguration/config_db.php');
// Assicurati che $_SESSION['username'] sia impostata e sicura
session_start();
if (!isset($_SESSION['username'])) {
    die(json_encode(array('error' => 'Sessione non valida')));
}

$username = $conn->real_escape_string($_SESSION['username']);

// Prendi il titolo della lezione dal corpo della richiesta POST
$data = json_decode(file_get_contents('php://input'), true);
$title = $conn->real_escape_string($data['title']);
$language = $conn->real_escape_string($data['language']);
$numLezioni = $conn->real_escape_string($data['numLezione']);

$stmt = $conn->prepare("SELECT c.id
                       FROM iscrizione i
                       JOIN corso c ON i.corso = c.id
                       WHERE i.username = ? AND c.lingua = ?"); //*QUESTA QUERY RECUPERA L'ID DEL CORSO A CUI L'USERNAME IN INPUT E' ISCRITTO E CHE VIENE SPECIFICATO
$stmt->bind_param("ss", $username, $language);
$stmt->execute();
$result = $stmt->get_result();


// Creo un array contenente tutti gli id dei corsi a cui è iscritto l'utente
$corsiUtente = [];
while ($row = $result->fetch_assoc()) {
    $corsiUtente[] = $row['id'];
}

// Recupero i record della tabella lezione per i corsi a cui l'utente è iscritto
$queryLezioni = "SELECT titolo, corso FROM lezione";
$stmtLezioni = $conn->prepare($queryLezioni);
$stmtLezioni->execute();
$resultLezioni = $stmtLezioni->get_result();

$lezioni = [];
// Creo un array contenente tutti i titoli delle lezioni per i corsi a cui l'utente è iscritto
while ($rowLezioni = $resultLezioni->fetch_assoc()) {
    if (in_array($rowLezioni['corso'], $corsiUtente)) {
        $lezioni[] = $rowLezioni['titolo'];
    }
}


if (!(in_array($title, $lezioni))) {
    // Inserisci il titolo della lezione e l'ID del corso nella tabella lezione
    $sql = "INSERT INTO lezione (titolo, corso) VALUES ('$title', '$corsiUtente[0]')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Lezione completata con successo'));
    } else {
        echo json_encode(array('error' => 'Errore durante l\'inserimento della lezione: ' . $conn->error));
    }

    //AGGIORNAMENTO DEL PROGRESSO
    $queryCount = "SELECT count(*) as count FROM lezione WHERE corso = '$corsiUtente[0]'";
    $stmtCount = $conn->prepare($queryCount);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $rowCount = $resultCount->fetch_assoc();
    $count = $rowCount['count'];

    $sql = "UPDATE corso SET progresso = ($count/$numLezioni) WHERE id = '$corsiUtente[0]'";
    $conn->query($sql);
} else {
    // Se l'ID del corso non è stato trovato per l'username fornito
    echo json_encode(array('error' => 'Nessun corso trovato per l\'utente specificato'));
}

$stmt->close();
$conn->close();
