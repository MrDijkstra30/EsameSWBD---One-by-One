<!DOCTYPE html>
<html lang="it">
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['is_logged_in'])) {
    // Redirect a pagina di non autorizzazione
    header('Location: ../../../Authentification/unauthorized.php', 401);
    exit;
}
require_once('../../../DBconfiguration/config_db.php');

//*QUESTA QUERY RECUPERA LA DIFFICOLTA' DEI CORSI A CUI L'USERNAME IN INPUT E' ISCRITTO
$stmt = $conn->prepare("SELECT c.difficolta FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'spagnolo'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$difficolta = $row['difficolta'];

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratica Spagnolo</title>
    <link rel="stylesheet" href="../pratica.css">
    <script>
        function checkDifficulty() {
            if (<?php echo $difficolta; ?> !== 0) {
                return true;
            } else {
                alert("Per accedere a questo modulo avanza di livello nella Teoria!");
                return false;
            }
        }
    </script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/home_profile/HomePage.php">
                <img src="../../../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div style="display: flex; align-items: center; justify-content: center;">
            <h1 style="margin-right: 10px;">MODALITA' PRATICA DI SPAGNOLO </h1>
            <img src="../../../photo/esp.png" alt="Bandiera Spagnolo" style="height: 10%; max-width: 10%;">
        </div>

        <div class="user-info">
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">
                    <img src="../../../photo/profile.png" alt="Profilo" class="profile-icon">
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="http://localhost/esameSWBD/Home_Profile/profile.php">Profilo</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/logout.php">Logout</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/HomePage.php">Home</a>
                </div>
            </div>
        </div>
    </nav>


    <div class="course-container">

        <div class="course-card">
            <div class="course-info">
                <h3>MODULO 1</h3>
                <p>In questo modulo potrai esercitarti su tutti gli argomenti del corso che compongono la modalità <strong>facile</strong></p>
                <form action="modulo1.php" method="post" class="signup-form">
                    <button type="submit" class="signup-button">Procedi</button>
                </form>
            </div>
        </div>

        <div class="course-card">
            <div class="course-info">
                <h3>MODULO 2</h3>
                <p>In questo modulo potrai esercitarti su tutti gli argomenti del corso che compongono la modalità <strong>difficile</strong></p>
                <form action="modulo2.php" method="post" class="signup-form" onsubmit="return checkDifficulty()">
                    <button type="submit" class="signup-button" id="submitButton">Procedi</button>
                </form>
            </div>
        </div>


        <div class="footer">
            <div style="display: flex; justify-content: space-between;">
                <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/corsoSpagnolo.php'">Torna indietro</button>
                <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/pratica/praticaSpa/storico.php'">Vedi Storico</button>
            </div>
        </div>

        <script src="../pratica.js"></script>
</body>

</html>