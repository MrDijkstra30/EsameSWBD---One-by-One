<?php
session_start();
require_once('../DBconfiguration/config_db.php');

$error_message = ''; // Inizializza la variabile del messaggio di errore

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Password non criptata inserita dall'utente

    // Query per ottenere la password criptata dal database
    $sql = "SELECT username, password FROM utente WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Gestione degli errori di query
        $error_message = "Errore nella query: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verifica se la password inserita dall'utente corrisponde alla password criptata nel database
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['is_logged_in'] = true;
            header("Location: ../Home_profile/HomePage.php");
            exit();
        } else {
            // Credenziali errate
            $error_message = "Password errata. Riprova.";
        }
    } else {
        // Username non trovato nel database
        $error_message = "Utente non trovato. Riprova.";
    }

    // Chiudi la connessione
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso</title>
    <link rel="stylesheet" href="regAccesso.css">
</head>

<body>
    <div class="container">
        <h2>Accesso</h2>
        <form action="" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <br>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br>
            <input type="submit" value="Accedi">
            <div id="error-message" class="error-message"></div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const errorMessage = "<?php echo $error_message; ?>";
            if (errorMessage) {
                const errorDiv = document.getElementById("error-message");
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = "block";
            }
        });
    </script>
</body>

</html>