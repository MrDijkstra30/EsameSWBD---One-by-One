<!DOCTYPE html>
<html lang="it">
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['is_logged_in'])) {
    // Redirect a pagina di non autorizzazione
    header('Location: ../Authentification/unauthorized.php', 401);
    exit;
}
require_once('../DBconfiguration/config_db.php');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/Home_Profile/HomePage.php">
                <img src="../photo/logo.png" alt="Logo">
            </a>
        </div>

        <h1>Informazioni <?php echo $_SESSION['username']; ?></h1>

        <div class="user-info">
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">
                    <img src="../photo/profile.png" alt="Profilo" class="profile-icon">
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="http://localhost/esameSWBD/Home_Profile/HomePage.php">Home</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/logout.php">Logout</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/HomeCorsi.php">I tuoi corsi</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-info">
            <?php
            //recupero dati anagrafici
            $query = "SELECT nome, cognome, email FROM utente WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $email = $row['email'];

            //recupero lezioni dalla tabella corso che però siano associati al'username salvato nella tabella iscrizione
            $query = "SELECT c.lingua, c.progresso FROM corso c JOIN iscrizione i ON c.id = i.corso WHERE i.username = ?;";
            $stmt = $conn->prepare($query);

            // Verifica se la preparazione della query è riuscita
            if (!$stmt) {
                die("Preparation failed: " . $conn->error . " | Query: " . $query);
            }

            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->get_result();

            $lezioni = [];
            $progresso = [];
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['lingua'])) {
                    $lezioni[] = $row['lingua'];
                    $progresso[] = $row['progresso'];
                }
            }

            $stmt->close();
            $conn->close();
            ?>
            <p><strong>Nome:</strong> <?php echo $nome ?></p>
            <p><strong>Cognome:</strong> <?php echo $cognome ?></p>
            <p><strong>Email:</strong> <?php echo $email ?></p>
            <div class="paragraph-container">
                <p><strong>Lezioni seguite - Progresso:</strong></p>
                <?php if (count($lezioni) > 0) : ?>
                    <?php for ($i = 0; $i < count($lezioni); $i++) : ?>
                        <p><?php echo htmlspecialchars($lezioni[$i] . ' - ' . $progresso[$i] * 100 . '%'); ?></p>
                    <?php endfor; ?>
                <?php else : ?>
                    <p style="font-style: italic">Nessuna lezione seguita</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="profile.js"> </script>
</body>

</html>