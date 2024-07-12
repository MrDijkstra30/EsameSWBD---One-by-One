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
$numLezioni = 5;

//* RECUPERA L'ID E LA DIFFICOLTA' DEL CORSO A CUI L'USERNAME IN INPUT E' ISCRITTO in base alla lingua
$stmt = $conn->prepare("SELECT c.id, c.difficolta FROM iscrizione i JOIN corso c ON i.corso = c.id WHERE i.username = ? AND c.lingua = 'italiano'");
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
    <title>Lezioni di Italiano</title>
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
                <div class="box-title">Articoli</div>
                <div class="box-content">Gli articoli in italiano sono parole che accompagnano i sostantivi per specificarne il genere
                    (maschile o femminile) e il numero (singolare o plurale). Si dividono in due categorie principali: determinativi e indeterminativi.
                    <ul>
                        <li>Articoli determinativi (Maschile Singolare): <strong>il, lo</strong></li>
                        <li>Articoli determinativi (Maschile Plurale): <strong>i, gli</strong></li>
                        <li>Articoli determinativi (Femminile Singolare): <strong>la, l'</strong></li>
                        <li>Articoli determinativi (Femminile Plurale): <strong>le</strong></li>
                        <li>Articoli indeterminativi (Maschile Singolare): <strong>un, uno</strong></li>
                        <li>Articoli indeterminativi (Femminile Singolare): <strong>una, un'</strong></li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Articoli" language="italiano" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($boxToShow == 1) ? 'block' : 'none'; ?>">
                <div class="box-title">Aggettivi</div>
                <div class="box-content">
                    Gli aggettivi in italiano sono parole che si usano per <strong>descrivere o qualificare i sostantivi, fornendo informazioni aggiuntive sulle loro caratteristiche</strong>.
                    Si accordano in genere (maschile o femminile) e numero (singolare o plurale) con il sostantivo a cui si riferiscono.
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Aggettivi" language="italiano" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($difficolta && $boxToShow == 2) ? 'block' : 'none'; ?>">
                <div class="box-title">Pronomi Personali</div>
                <div class="box-content">
                    I pronomi personali in italiano sono <strong>usati per sostituire i nomi delle persone, animali o cose per evitare ripetizioni e rendere il discorso più fluido. </strong>
                    <ul>
                        <li>Pronomi Personali Soggetto (Singolare): <strong>io, tu, egli</strong></li>
                        <li>Pronomi Personali Soggetto (Plurale): <strong>noi, voi, essi</strong></li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Pronomi Personali" language="italiano" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($difficolta && $boxToShow == 3) ? 'block' : 'none'; ?>">
                <div class="box-title">Verbo Essere</div>
                <div class="box-content ">
                    Coniugazione al tempo Presente:
                    <ul>
                        <li>Io sono</li>
                        <li>Tu sei</li>
                        <li>Egli è</li>
                        <li>Noi siamo</li>
                        <li>Voi siete</li>
                        <li>Essi sono</li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Verbo Essere" language="italiano" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
            <div class="box" style="display:<?php echo ($difficolta && $boxToShow == 4) ? 'block' : 'none'; ?>">
                <div class="box-title">Verbo Avere</div>
                <div class="box-content ">
                    Coniugazione al tempo Presente:
                    <ul>
                        <li>Io ho</li>
                        <li>Tu hai</li>
                        <li>Egli ha</li>
                        <li>Noi abbiamo</li>
                        <li>Voi avete</li>
                        <li>Essi hanno</li>
                    </ul>
                </div>
                <button type="button" onclick="addLesson(this)" data-title="Verbo Avere" language="italiano" totLezione="<?php echo $numLezioni; ?>">Capito!</button>
            </div>
        </div>

        <div class="message-box" style="display:<?php echo (($boxToShow > 1) && !($difficolta)) ? 'block' : 'none' ?>;">
            <h3>Hai terminato le lezioni della modalità corrente</h3>
            <h5>Per sbloccare le prossime</h5>
            <button class="button" onclick="nextLevel(this); location.reload();" id-lezione="<?php echo $corsoUtente; ?>">Avanza di livello</button>
        </div>


        <div class="footer">
            <button class="navigate-button" onclick="location.href='http://localhost/esameSWBD/corsi/corsoItaliano.php'">Torna indietro</button>
            <button class="next-box" onclick="incrementBoxToShow(); location.reload();" style="display:<?php echo $boxToShow == 4 ? 'none' : 'block'; ?>">Lezione Successiva</button>
            <button class="prev-box" onclick="decrementBoxToShow(); location.reload();" style="display:<?php echo $boxToShow == 0 ? 'none' : 'block'; ?>">Lezione Precedente</button>
        </div>

    </div>

</body>

</html>