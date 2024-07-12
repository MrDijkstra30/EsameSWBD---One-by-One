<?php
require_once('../DBconfiguration/config_db.php');

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Prepara la query
    $query = "SELECT COUNT(*) AS count FROM utente WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Controlla se l'username esiste
    $exists = $result['count'] > 0;

    // Restituisce la risposta come JSON
    echo json_encode(['exists' => $exists]);

    exit;
}
