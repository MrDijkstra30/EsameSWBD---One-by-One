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
//* RECUPERA il progresso DEL CORSO A CUI L'USERNAME IN INPUT E' ISCRITTO in base alla lingua
$stmt = $conn->prepare("SELECT c.progresso FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'spagnolo'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$progresso = $row['progresso'];
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corso Spagnolo</title>
    <link rel="stylesheet" href="corsi.css">
    <script>
        function checkProgress() {
            if (<?php echo $progresso; ?> == 1) {
                return true;
            } else {
                alert("Per accedere a questo modulo devi COMPLETARE IL CORSO!");
                return false;
            }
        }
    </script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/home_profile/HomePage.php">
                <img src="../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div style="display: flex; align-items: center; justify-content: center;">
            <h1 style="margin-right: 10px;">CORSO DI SPAGNOLO </h1>
            <img src="../photo/esp.png" alt="Bandiera Spagnola" style="height: 10%; max-width: 10%;">
        </div>

        <div class="user-info">
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">
                    <img src="../photo/profile.png" alt="Profilo" class="profile-icon">
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
                <h3>Lezioni Teoriche</h3>
                <p>In questa sezione troverai i concetti base della lingua spagnola.</p>
                <form action="../corsi/teoria/teoriaSpa.php" method="post" class="signup-form">
                    <input type="hidden" name="idCorso" value="spagnolo">
                    <button type="submit" class="signup-button">Procedi</button>
                </form>
            </div>
        </div>

        <div class="course-card">
            <div class="course-info">
                <h3>Modalità pratica</h3>
                <p>In questa sezione potrai esercitarti sui concetti imparati fin'ora</p>
                <form action="pratica/praticaSpa" method="post" class="signup-form">
                    <input type="hidden" name="idCorso" value="spagnolo">
                    <button type="submit" class="signup-button">Procedi</button>
                </form>
            </div>
        </div>

        <div class="course-card">
            <div class="course-info">
                <h3>Quiz di autovalutazione</h3>
                <p>In questa sezione potrai metterti alla prova su tutto quello che hai imaprato durante il corso</p>
                <form action="quiz/quizSpagnolo.php" method="post" class="signup-form" onsubmit="return checkProgress()">
                    <input type="hidden" name="idCorso" value="spagnolo">
                    <button type="submit" class="signup-button">Procedi</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/home_profile/HomeCorsi.php'">Torna indietro</button>
    </div>

    <script src="corsi.js"></script>
</body>

</html>