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

//* RECUPERA L'ID DEL CORSO A CUI L'USERNAME IN INPUT E' ISCRITTO in base alla lingua
$stmt = $conn->prepare("SELECT c.id FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'inglese'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$corso = $row['id'];

// Inizializza le variabili di sessione se non sono già state impostate
if (!isset($_SESSION['card_states_quiz'])) {
    $_SESSION['card_states_quiz'] = array();
}

$_SESSION['giuste_quiz'] = 0;
$_SESSION['sbagliate_quiz'] = 0;

// Gestisce l'incremento delle variabili e aggiorna lo stato delle card
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reset_quiz'])) {
        $_SESSION['giuste_quiz'] = 0;
        $_SESSION['sbagliate_quiz'] = 0;
        $_SESSION['card_states_quiz'] = array();
    } elseif (isset($_POST['card_quiz'])) {
        $card_id = $_POST['card_quiz'];
        if (!isset($_SESSION['card_states_quiz'][$card_id])) {
            if (isset($_POST['giuste_quiz'])) {
                $_SESSION['giuste_quiz']++;
                $_SESSION['card_states_quiz'][$card_id] = 'giuste_quiz';
            } elseif (isset($_POST['sbagliate_quiz'])) {
                $_SESSION['sbagliate_quiz']++;
                $_SESSION['card_states_quiz'][$card_id] = 'sbagliate_quiz';
            }
        }
    }
}

// Numero totale di esercizi
$total_cards = 5;
$all_answered = count($_SESSION['card_states_quiz']) === $total_cards;

// Aggiorna la tabella modalitapratica se tutte le domande sono state risposte
if ($all_answered) {
    try {
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        $numDomande = $total_cards;
        $numDomCorretta = $_SESSION['giuste_quiz'];
        $stmt = $conn->prepare("INSERT INTO quiz (numDomande, numDomCorretta, corso) VALUES (?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("iii", $numDomande, $numDomCorretta, $corso);
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
    <title>Quiz Inglese</title>
    <link rel="stylesheet" href="quiz.css">
    <style>
        .reset_quiz-button-container {
            position: fixed;
            bottom: 20px;
            right: 10px;
            color: black;
            padding: 10px 20px;
            border: 1px solid black;
            border-radius: 4px;
            cursor: pointer;
            display: <?= $all_answered ? 'block' : 'none' ?>;
        }

        .reset_quiz-button-container button {
            padding: 10px 20px;
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
                <img src="../../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div style="display: flex; align-items: center; justify-content: center;">
            <h1 style="margin-right: 10px;">QUIZ INGLESE</h1>
            <img src="../../photo/uk.jpg" alt="Bandiera Inglese" style="height: 10%; max-width: 10%;">
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

    <div class="module-container">
        <div class="module-card <?= isset($_SESSION['card_states_quiz'][1]) ? ($_SESSION['card_states_quiz'][1] == 'giuste_quiz' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>DOMANDA 1</h3>
                <p>In inglese AN è l'unico articolo indeterminativo</p>
                <form method="post">
                    <input type="hidden" name="card_quiz" value="1">
                    <button type="submit" name="sbagliate_quiz" <?= isset($_SESSION['card_states_quiz'][1]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="giuste_quiz" <?= isset($_SESSION['card_states_quiz'][1]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>

        <div class="module-card <?= isset($_SESSION['card_states_quiz'][2]) ? ($_SESSION['card_states_quiz'][2] == 'giuste_quiz' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>DOMANDA 2</h3>
                <p>Dato l'aggettivo 'smart' il relativo avverbio in inglese è</p>
                <form method="post">
                    <input type="hidden" name="card_quiz" value="2">
                    <button type="submit" name="sbagliate_quiz" <?= isset($_SESSION['card_states_quiz'][2]) ? 'disabled' : '' ?>>Smarties</button>
                    <button type="submit" name="giuste_quiz" <?= isset($_SESSION['card_states_quiz'][2]) ? 'disabled' : '' ?>>Smartly</button>
                </form>
            </div>
        </div>

        <div class="module-card <?= isset($_SESSION['card_states_quiz'][3]) ? ($_SESSION['card_states_quiz'][3] == 'giuste_quiz' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>DOMANDA 3</h3>
                <p>Nella seguente frase, "I know you like programming", 'I' e 'you' sono entrambi pronomi personali soggetto</p>
                <form method="post">
                    <input type="hidden" name="card_quiz" value="3">
                    <button type="submit" name="giuste_quiz" <?= isset($_SESSION['card_states_quiz'][3]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="sbagliate_quiz" <?= isset($_SESSION['card_states_quiz'][3]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>
        <div class="module-card <?= isset($_SESSION['card_states_quiz'][4]) ? ($_SESSION['card_states_quiz'][4] == 'giuste_quiz' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>DOMANDA 4</h3>
                <p>Il verbo essere nella seguente frase, "I have a dog", è coniugato correttamente</p>
                <form method="post">
                    <input type="hidden" name="card_quiz" value="4">
                    <button type="submit" name="giuste_quiz" <?= isset($_SESSION['card_states_quiz'][4]) ? 'disabled' : '' ?>>Vero</button>
                    <button type="submit" name="sbagliate_quiz" <?= isset($_SESSION['card_states_quiz'][4]) ? 'disabled' : '' ?>>Falso</button>
                </form>
            </div>
        </div>
        <div class="module-card <?= isset($_SESSION['card_states_quiz'][5]) ? ($_SESSION['card_states_quiz'][5] == 'giuste_quiz' ? 'lightgreen' : 'lightcoral') : '' ?>">
            <div class="module-info">
                <h3>DOMANDA 5</h3>
                <p>La coniugazione del verbo avere alla prima persona plurale è</p>
                <form method="post">
                    <input type="hidden" name="card_quiz" value="5">
                    <button type="submit" name="sbagliate_quiz" <?= isset($_SESSION['card_states_quiz'][5]) ? 'disabled' : '' ?>>It has</button>
                    <button type="submit" name="giuste_quiz" <?= isset($_SESSION['card_states_quiz'][5]) ? 'disabled' : '' ?>>We have</button>
                </form>
            </div>
        </div>
    </div>

    <div class="reset_quiz-button-container">
        <h4>Risultati salvati, puoi</h4>
        <form method="post">
            <button type="submit" name="reset_quiz">RIPROVARE!</button>
        </form>
        <button onclick="location.href='http://localhost/esameSWBD/corsi/quiz/storicoIng.php'">Vedi Storico</button>
    </div>

    <?php if ($all_answered) : ?>
        <h4 style="text-align: center"> Il tuo punteggio è di <?= $_SESSION['giuste_quiz'] . '/' . $total_cards ?> <h4>
            <?php endif; ?>

            <div class="footer">
                <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/corsoInglese.php'">Torna indietro</button>
            </div>

            <script src="quiz.js"></script>
</body>

</html>