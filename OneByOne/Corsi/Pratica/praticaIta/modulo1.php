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
$stmt = $conn->prepare("SELECT c.id FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'italiano'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$corso = $row['id'];

// Inizializza le variabili di sessione se non sono già state impostate
if (!isset($_SESSION['card_states_it'])) {
    $_SESSION['card_states_it'] = array();
}

$_SESSION['giuste_it'] = 0;
$_SESSION['sbagliate_it'] = 0;

// Gestisce l'incremento delle variabili e aggiorna lo stato delle card
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reset_it'])) {
        $_SESSION['giuste_it'] = 0;
        $_SESSION['sbagliate_it'] = 0;
        $_SESSION['card_states_it'] = array();
    } elseif (isset($_POST['card_it'])) {
        $card_id = $_POST['card_it'];
        if (!isset($_SESSION['card_states_it'][$card_id])) {
            if (isset($_POST['giuste_it'])) {
                $_SESSION['giuste_it']++;
                $_SESSION['card_states_it'][$card_id] = 'giuste_it';
            } elseif (isset($_POST['sbagliate_it'])) {
                $_SESSION['sbagliate_it']++;
                $_SESSION['card_states_it'][$card_id] = 'sbagliate_it';
            }
        }
    }
}

// Numero totale di esercizi
$total_cards = 3;
$all_answered = count($_SESSION['card_states_it']) === $total_cards;

// Aggiorna la tabella modalitapratica se tutte le domande sono state risposte
if ($all_answered) {
    try {
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        $numEsercizi = $total_cards;
        $numEseCorretto = $_SESSION['giuste_it'];
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
    <title>Pratica Italiano</title>
    <link rel="stylesheet" href="../pratica.css">
    <style>
        .reset_it-button-container {
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

        .reset_it-button-container button {
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
            <img src="../../../photo/ita.jpg" alt="Bandiera Italiana" style="height: 10%; max-width: 10%;">
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
        <div class="module-card <?= isset($_SESSION['card_states_it'][1]) ? ($_SESSION['card_states_it'][1] == 'giuste_it' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>ESERCIZIO 1</h3>
                <p>In italiano esiste un unica parola che corrisponde all'unico articolo determinativo</p>
                <form method="post">
                    <input type="hidden" name="card_it" value="1">
                    <button type="submit" name="sbagliate_it" <?= isset($_SESSION['card_states_it'][1]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="giuste_it" <?= isset($_SESSION['card_states_it'][1]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>

        <div class="module-card <?= isset($_SESSION['card_states_it'][2]) ? ($_SESSION['card_states_it'][2] == 'giuste_it' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>ESERCIZIO 2</h3>
                <p>L'articolo indeterminativo UN' è usato con nomi femminili che iniziano con una vocale, ma non tutti</p>
                <form method="post">
                    <input type="hidden" name="card_it" value="2">
                    <button type="submit" name="sbagliate_it" <?= isset($_SESSION['card_states_it'][2]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="giuste_it" <?= isset($_SESSION['card_states_it'][2]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>

        <div class="module-card <?= isset($_SESSION['card_states_it'][3]) ? ($_SESSION['card_states_it'][3] == 'giuste_it' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>ESERCIZIO 3</h3>
                <p>Nella frase "Il mio bellissimo cane ha 4 zampe" l'aggettivo è</p>
                <form method="post">
                    <input type="hidden" name="card_it" value="3">
                    <button type="submit" name="giuste_it" <?= isset($_SESSION['card_states_it'][3]) ? 'disabled' : '' ?>>Bellissimo</button>
                    <button type="submit" name="sbagliate_it" <?= isset($_SESSION['card_states_it'][3]) ? 'disabled' : '' ?>>4</button>
                </form>
            </div>
        </div>
    </div>

    <div class="reset_it-button-container">
        <form method="post">
            <button type="submit" name="reset_it">Risultati salvati, puoi RIPROVARE!</button>
        </form>
    </div>

    <?php if ($all_answered) : ?>
        <h4 style="text-align: center"> Il tuo punteggio è di <?= $_SESSION['giuste_it'] . '/' . $total_cards ?> <h4>
            <?php endif; ?>

            <div class="footer">
                <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/pratica/praticaIta/'">Torna indietro</button>
            </div>

            <script src="../pratica.js"></script>
</body>

</html>