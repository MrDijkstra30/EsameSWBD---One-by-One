<!DOCTYPE html>
<html lang="it">

<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['is_logged_in'])) {
    // Redirect a pagina di non autorizzazione
    header('Location: ../../Authentification/unauthorized.php', 401);
    exit;
}
require_once('../../DBconfiguration/config_db.php');


//* RECUPERA L'ID E LA DIFFICOLTA' DEL CORSO A CUI L'USERNAME IN INPUT E' ISCRITTO in base alla lingua
$stmt = $conn->prepare("SELECT c.id FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'inglese'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$corsoUtente = $row['id'];

// Query per recuperare i record
$sql = "SELECT * FROM quiz WHERE corso = '$corsoUtente'";
$result = mysqli_query($conn, $sql);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="quiz.css">
    <script src="quiz.js"></script>


    <title>Storico Inglese</title>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/home_profile/HomePage.php">
                <img src="../../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div style="display: flex; align-items: center; justify-content: center;">
            <h1 style="margin-right: 10px;">Storico Quiz</h1>
            <img src="../../photo/uk.jpg" alt="Bandiera Spagnolo" style="height: 10%; max-width: 10%;">
        </div>

        <div class="user-info">
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">
                    <img src="../../photo/profile.png" alt="Profilo" class="profile-icon">
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="http://localhost/esameSWBD/Home_Profile/profile.php">Profilo</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/logout.php">Logout</a>
                    <a href="http://localhost/esameSWBD/Home_Profile/HomePage.php">Home</a>
                </div>
            </div>
        </div>
    </nav>

    <?php if ($result) : ?>

        <table class="tabella">
            <?php
            echo '<thead>
    <tr>
        <th>ID</th>
        <th>Risposte corrette</th>
        <th>Totale Domande</th>
        </tr>
</thead>';

            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
            <td>' . $row["id"] . '</td>
            <td>' . $row["numDomCorretta"] . '</td>
            <td>' . $row["numDomande"] . '</td>
            </tr>';
            }
            echo '</tbody>';

            mysqli_close($conn);
            ?>
        </table>
    <?php else : ?>
        <p>Nessun record trovato</p>
    <?php endif; ?>

    <div class="footer">
        <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/corsoInglese.php'">Torna indietro</button>
    </div>
</body>

</html>