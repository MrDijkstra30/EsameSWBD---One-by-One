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

//variabile per tenere traccia delle info di lezioni per un più comodo eventuale aggiornameto
$numLezioni = 4;

//*QUESTA QUERY RECUPERA TUTTI GLI ID DEI CORSI A CUI L'USERNAME IN INPUT E' ISCRITTO
$stmt = $conn->prepare("SELECT c.id, c.difficolta FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'spagnolo'");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$corsoUtente = $row['id'];
$difficolta = $row['difficolta'];

$lezioni = [];
// Recupero i record della tabella lezione per i corsi a cui l'utente è iscritto
$queryLezioni = "SELECT titolo, corso FROM lezione";
$stmtLezioni = $conn->prepare($queryLezioni);
$stmtLezioni->execute();
$resultLezioni = $stmtLezioni->get_result();

// Creo un array associativo per le lezioni per i corsi a cui l'utente è iscritto
while ($rowLezioni = $resultLezioni->fetch_assoc()) {
    if ($rowLezioni['corso'] == $corsoUtente) {
        $lezioni[] = $rowLezioni['titolo'];
    }
}

// Inizializza la variabile $boxToShow se non è già impostata
if (!isset($_SESSION['boxToShow'])) {
    $_SESSION['boxToShow'] = 0;
}

// Recupera il valore corrente della variabile
$boxToShow = $_SESSION['boxToShow'];

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lezioni di Spagnolo</title>
    <link rel="stylesheet" href="teoria.css">
    <script src="teoria.js"></script>
    <script>
        //*Codice per l'aggiornamento dinamico del colore dei box
        const lezioni = <?php echo json_encode($lezioni); ?>;
        const difficolta = <?php echo json_encode($corsoUtente); ?>;

        // Funzione per aggiornare il colore di sfondo dei box
        function updateBoxColors() {
            document.querySelectorAll('.box').forEach(box => {
                const title = box.querySelector('.box-title').textContent;
                if (lezioni.includes(title)) {
                    box.style.backgroundColor = 'lightgreen';
                } else {
                    box.style.backgroundColor = 'white';
                }
            });
        }

        // Funzione per gestire l'incremento della variabile boxToShow tramite AJAX
        function incrementBoxToShow() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'increment_box.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Aggiorna il valore visualizzato
                    document.getElementById('boxToShowValue').textContent = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Funzione per gestire l' decremento della variabile boxToShow tramite AJAX
        function decrementBoxToShow() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'decrement_box.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Aggiorna il valore visualizzato
                    document.getElementById('boxToShowValue').textContent = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Esegui la funzione quando il documento è pronto
        document.addEventListener('DOMContentLoaded', updateBoxColors);
    </script>
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <a href="https://localhost/esameSWBD/home_profile/HomePage.php">
                <img src="../../photo/logo.png" alt="Logo">
            </a>
        </div>

        <div class="welcome-message">
            <h1>Corso seguito da <?php echo $_SESSION['username']; ?></h1>
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

    <!-- messaggio di notifica -->
    <div id="message-container"></div>
    <h3> La difficoltà del corso è: <?php echo $difficolta ? "Difficile" : "Facile"; ?></h3>


    <div class="container">
        <div class="boxes">
            <div class="box" style="display:<?php echo ($boxToShow == 0) ? 'block' : 'none'; ?>">
                <div class="box-title">Articulo</div>
                <div class="box-content">
                    Gli articoli in spagnolo sono parole che accompagnano i sostantivi per specificarne il genere
                    (maschile o femminile) e il numero (singolare o plurale). Si dividono in due categorie principali: determinativi e indeterminativi.
                    <ul>
                        <li>Articoli determinativi (Maschile Singolare): <strong>el</strong></li>
                        <li>Articoli determinativi (Maschile Plurale): <strong>los</strong></li>
                        <li>Articoli determinativi (Femminile Singolare): <strong>la</strong></li>
                        <li>Articoli determinativi (Femminile Plurale): <strong>las</strong></li>
                        <li>Articoli indeterminativi (Maschile Singolare/Plurale): <strong>un/unos</strong></li>
                        <li>Articoli indeterminativi (Femminile Singolare/Plurale): <strong>una/unas</strong></li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Articulo" language="spagnolo" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($boxToShow == 1) ? 'block' : 'none'; ?>">
                <div class="box-title">Pronombres</div>
                <div class="box-content">
                    I pronomi personali soggetto in spagnolo sono parole che sostituiscono i nomi delle persone o cose che compiono l'azione del verbo. Sono usati per identificare chi sta compiendo l'azione in una frase.
                    <ul>
                        <li>Pronomi Personali Soggetto (Singolare): <strong>yo, tu, el/ella</strong></li>
                        <li>Pronomi Personali Soggetto (Plurale): <strong>Nosotros/Nosotras, Vosotros/Vosotras, Ellos/Ellas</strong></li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Pronombres" language="spagnolo" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($difficolta && $boxToShow == 2) ? 'block' : 'none'; ?>">
                <div class="box-title">Ser</div>
                <div class="box-content">
                    Coniugazione del Verbo "Ser" al Presente:
                    <ul>
                        <li>Yo soy</li>
                        <li>Tú eres</li>
                        <li>Él/Ella/Usted es</li>
                        <li>Nosotros/Nosotras somos</li>
                        <li>Vosotros/Vosotras sois</li>
                        <li>Ellos/Ellas/Ustedes son</li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Ser" language="spagnolo" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($difficolta && $boxToShow == 3) ? 'block' : 'none'; ?>">
                <div class="box-title">Haber</div>
                <div class="box-content">
                    Coniugazione del Verbo "Haber" al Presente:
                    <ul>
                        <li>Yo he</li>
                        <li>Tú has</li>
                        <li>Él/Ella/Usted ha</li>
                        <li>Nosotros/Nosotras hemos</li>
                        <li>Vosotros/Vosotras habéis</li>
                        <li>Ellos/Ellas/Ustedes han</li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Haber" language="spagnolo" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
        </div>

        <div class="message-box" style="display:<?php echo (($boxToShow > 1) && !($difficolta)) ? 'block' : 'none' ?>;">
            <h3>Hai terminato le lezioni della modalità corrente</h3>
            <h5>Per sbloccare le prossime</h5>
            <button class="button" onclick="nextLevel(this); location.reload();" id-lezione="<?php echo $corsoUtente; ?>">Avanza di livello</button>
        </div>

        <div class="footer">
            <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/corsoSpagnolo.php'">Torna indietro</button>
            <button class="next-box" onclick="incrementBoxToShow(); location.reload();" style="display:<?php echo $boxToShow == 3 ? 'none' : 'block'; ?>">Lezione Successiva</button>
            <button class="prev-box" onclick="decrementBoxToShow(); location.reload();" style="display:<?php echo $boxToShow == 0 ? 'none' : 'block'; ?>">Lezione Precedente</button>
        </div>
    </div>

</body>

</html>