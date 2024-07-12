<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['is_logged_in'])) {
    // Redirect a pagina di non autorizzazione
    header('Location: ../Authentification/unauthorized.php', 401);
    exit;
}
require_once('../DBconfiguration/config_db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['idCorso'])) {
        $idCorso = $_POST['idCorso'];
        $numLezioni = $_POST['numLezioni'];

        // Prepara e lega
        $stmt = $conn->prepare("INSERT INTO corso (lingua, durata) VALUES (?, ?)");
        $stmt->bind_param("ss", $idCorso, $numLezioni);

        // Esegui la query
        if ($stmt->execute()) {
            // recupera l'id del corso appena inserito
            $stmt->store_result();
            $idCorso = $stmt->insert_id;

            $stmt = $conn->prepare("INSERT INTO iscrizione (username, corso, dataInizio) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $_SESSION['username'], $idCorso);
            if ($stmt->execute()) {
                header('Location: successfulCourseSignup.php');
            } else {
                header('Location: errorCourseSignup.php', 401);
            }
        } else {
            echo "Errore corso: " . $stmt->error;
        }

        // Chiudi la connessione
        $stmt->close();
        $conn->close();
    } else {
        header('Location: ../Authentification/unauthorized.php', 401);
    }
}
