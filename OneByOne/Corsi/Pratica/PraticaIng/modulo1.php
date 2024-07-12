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

//* RECUPERA L'ID DEL CORSO A CUI L'USERNAME IN INPUT E' ISCRITTO in base alla lingua
$stmt = $conn->prepare("SELECT c.id FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'inglese'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$corso = $row['id'];

// Inizializza le variabili di sessione se non sono già state impostate
if (!isset($_SESSION['card_states'])) {
    $_SESSION['card_states'] = array();
}

$_SESSION['giuste'] = 0;
$_SESSION['sbagliate'] = 0;

// Gestisce l'incremento delle variabili e aggiorna lo stato delle card
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reset'])) {
        $_SESSION['giuste'] = 0;
        $_SESSION['sbagliate'] = 0;
        $_SESSION['card_states'] = array();
    } elseif (isset($_POST['card'])) {
        $card_id = $_POST['card'];
        if (!isset($_SESSION['card_states'][$card_id])) {
            if (isset($_POST['giuste'])) {
                $_SESSION['giuste']++;
                $_SESSION['card_states'][$card_id] = 'giuste';
            } elseif (isset($_POST['sbagliate'])) {
                $_SESSION['sbagliate']++;
                $_SESSION['card_states'][$card_id] = 'sbagliate';
            }
        }
    }
}

// Numero totale di esercizi
$total_cards = 3;
$all_answered = count($_SESSION['card_states']) === $total_cards;

// Aggiorna la tabella modalitapratica se tutte le domande sono state risposte
if ($all_answered) {
    try {
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        $numEsercizi = $total_cards;
        $numEseCorretto = $_SESSION['giuste'];
        $stmt = $conn->prepare("INSERT INTO modalitapratica (numEsercizi, numEseCorretto, corso) VALUES (?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("iii", $numEsercizi, $numEseCorretto, $corso);
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        $stmt->close();
        $conn->close();
        $_SESSION['updated_db'] = true; // Evita inserimenti multipli
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratica Inglese</title>
    <link rel="stylesheet" href="../pratica.css">
    <style>
        .reset-button-container {
            position: fixed;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: <?= $all_answered ? 'block' : 'none' ?>;
        }

        .reset-button-container button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/home_profile/HomePage.php">
                <img src="../../../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div style="display: flex; align-items: center; justify-content: center;">
            <h1 style="margin-right: 10px;">MODULO 1</h1>
            <img src="../../../photo/uk.jpg" alt="Bandiera Inglese" style="height: 10%; max-width: 10%;">
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

    <div class="module-container">
        <div class="module-card <?= isset($_SESSION['card_states'][1]) ? ($_SESSION['card_states'][1] == 'giuste' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>ESERCIZIO 1</h3>
                <p>In inglese THE è l'unico articolo determinativo</p>
                <form method="post">
                    <input type="hidden" name="card" value="1">
                    <button type="submit" name="giuste" <?= isset($_SESSION['card_states'][1]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="sbagliate" <?= isset($_SESSION['card_states'][1]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>

        <div class="module-card <?= isset($_SESSION['card_states'][2]) ? ($_SESSION['card_states'][2] == 'giuste' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>ESERCIZIO 2</h3>
                <p>L'articolo indefinito AN è usato con nomi che iniziano con una vocale, ma non tutti</p>
                <form method="post">
                    <input type="hidden" name="card" value="2">
                    <button type="submit" name="sbagliate" <?= isset($_SESSION['card_states'][2]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="giuste" <?= isset($_SESSION['card_states'][2]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>

        <div class="module-card <?= isset($_SESSION['card_states'][3]) ? ($_SESSION['card_states'][3] == 'giuste' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>ESERCIZIO 3</h3>
                <p>Dato l'aggettivo inglese 'quick', il corrispettivo avverbio è</p>
                <form method="post">
                    <input type="hidden" name="card" value="3">
                    <button type="submit" name="giuste" <?= isset($_SESSION['card_states'][3]) ? 'disabled' : '' ?>>Quickly</button>
                    <button type="submit" name="sbagliate" <?= isset($_SESSION['card_states'][3]) ? 'disabled' : '' ?>>Quickest</button>
                </form>
            </div>
        </div>
    </div>


    <?php if ($all_answered) : ?>
        <h4 style="text-align: center"> Il tuo punteggio è di <?= $_SESSION['giuste'] . '/' . $total_cards ?> <h4>
            <?php endif; ?>

            <div class="reset-button-container">
                <form method="post">
                    <button type="submit" name="reset">Risultati salvati, puoi RIPROVARE!</button>
                </form>
            </div>

            <div class="footer">
                <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/pratica/praticaIng/'">Torna indietro</button>
            </div>

            <script src="../pratica.js"></script>
</body>

</html>