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

//recupero lezioni dalla tabella corso che però siano associati al'username salvato nella tabella iscrizione
$query = "SELECT c.lingua FROM corso c JOIN iscrizione i ON c.id = i.corso WHERE i.username = ?;";
$stmt = $conn->prepare($query);

// Verifica se la preparazione della query è riuscita
if (!$stmt) {
    die("Preparation failed: " . $conn->error . " | Query: " . $query);
}

$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

$lezioni = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['lingua'])) {
        $lezioni[] = $row['lingua'];
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/home_profile/HomePage.php">
                <img src="../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div class="welcome-message">
            <h1>Benvenuto, <?php echo $_SESSION['username']; ?>... scegli quale corso seguire</h1>
        </div>

        <div class="user-info">
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">
                    <img src="../photo/profile.png" alt="Profilo" class="profile-icon">
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="http://localhost/esameSWBD/Home_Profile/profile.php">Profilo</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/logout.php">Logout</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/HomeCorsi.php">I tuoi corsi</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="languages-container">

        <div class="language-card" style="display:<?php echo in_array('inglese', $lezioni) ? 'none' : 'block'; ?>">
            <a> <img src="../photo/uk.jpg" alt="Bandiera Inglese"> </a>
            <div class="language-info">
                <h3>Inglese</h3>
                <p>Il seguente corso è formato da <strong>5</strong> lezioni teoriche con relativi esercizi pratici ed un quiz finale di autovalutazione</p>
                <form action="iscrizioneCorso.php" method="post" class="signup-form">
                    <input type="hidden" name="idCorso" value="inglese">
                    <input type="hidden" name="numLezioni" value="5">
                    <button type="submit" class="signup-button">Iscriviti</button>
                </form>
            </div>
        </div>

        <div class="language-card" style="display:<?php echo in_array('italiano', $lezioni) ? 'none' : 'block'; ?>">
            <a> <img src="../photo/ita.jpg" alt="Bandiera Italiana"> </a>
            <div class="language-info">
                <h3>Italiano</h3>
                <p>Il seguente corso è formato da <strong>5</strong> lezioni teoriche con relativi esercizi pratici ed un quiz finale di autovalutazione</p>
                <form action="iscrizioneCorso.php" method="post" class="signup-form">
                    <input type="hidden" name="idCorso" value="italiano">
                    <input type="hidden" name="numLezioni" value="5">
                    <button type="submit" class="signup-button">Iscriviti</button>
                </form>
            </div>
        </div>

        <div class="language-card" style="display:<?php echo in_array('spagnolo', $lezioni) ? 'none' : 'block'; ?>">
            <a> <img src="../photo/esp.png" alt="Bandiera Spagnola"> </a>
            <div class="language-info">
                <h3>Spagnolo</h3>
                <p>Il seguente corso è formato da <strong>4</strong> lezioni teoriche con relativi esercizi pratici ed un quiz finale di autovalutazione</p>
                <form action="iscrizioneCorso.php" method="post" class="signup-form">
                    <input type="hidden" name="idCorso" value="spagnolo">
                    <input type="hidden" name="numLezioni" value="4">
                    <button type="submit" class="signup-button">Iscriviti</button>
                </form>
            </div>
        </div>

        <div class="languages-container">
            <h2>Nessun corso disponibile</h2>
        </div>

        <script src="home.js"></script>
</body>

</html>