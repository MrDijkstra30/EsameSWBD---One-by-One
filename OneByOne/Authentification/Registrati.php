<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="regAccesso.css">

    <?php
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    require_once('../DBconfiguration/config_db.php');
    ?>
</head>

<body>

    <div class="container">
        <h2>Registrazione</h2>
        <form method="post" id="regForm">
            <input type="text" id="nome" name="nome" placeholder="Nome" required>
            <div id="nomeError" class="error-message"></div>
            <br>
            <input type="text" id="cognome" name="cognome" placeholder="Cognome" required>
            <div id="cognomeError" class="error-message"></div>
            <br>
            <input type="text" id="email" name="email" placeholder="E-mail" required>
            <div id="emailError" class="error-message"></div>
            <br>
            <input type="text" id="username" name="username" placeholder="Username" required>
            <div id="usernameError" class="error-message"></div>
            <br>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div id="passwordError" class="error-message"></div>
            <br>
            <input type="submit" value="Registrati">
        </form>
    </div>

    <script src="register.js"></script>

    <?php
    // Controlla se si è arrivati in questa pagina tramite post dal form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Riceve i valori dal form
        $nome = $_POST["nome"];
        $cognome = $_POST["cognome"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash della password
        // Prepara e lega
        $stmt = $conn->prepare("INSERT INTO utente (nome, cognome, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $cognome, $email, $username, $password);


        // Esegue la query e mostra le relative pagine a seconda del risultato
        if ($stmt->execute()) {
            header("Location: successfulSignup.php");
        } else {
            header("Location: errorSignup.php");
        }

        // Chiude lo statement
        $stmt->close();
    }

    // Chiude la connessione
    $conn->close();
    ?>

</body>

</html>