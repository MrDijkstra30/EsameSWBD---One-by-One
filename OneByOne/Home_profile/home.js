//codice per la tendina dropdown del profilo
function toggleDropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function (event) {
    if (!event.target.matches('.dropbtn') && !event.target.matches('.profile-icon')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Controllo visibilità per la stampa del messaggio "nessun corso disponibile"
const languageCards = document.querySelectorAll('.language-card');
const noCoursesMessage = document.querySelector('.languages-container h2');

function checkCoursesAvailability() {
    let visibleCards = 0;

    for (const card of languageCards) {
        if (card.style.display !== 'none') {
            visibleCards++;
            break;
        }
    }

    if (visibleCards === 0) {
        noCoursesMessage.style.display = 'block';
    } else {
        noCoursesMessage.style.display = 'none';
    }
}

// Esegui il controllo all'avvio della pagina
checkCoursesAvailability();

// Esegui il controllo ogni volta che una card cambia visibilità
languageCards.forEach(card => card.addEventListener('transitionend', checkCoursesAvailability));