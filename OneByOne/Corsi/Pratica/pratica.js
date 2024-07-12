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

function addLesson(button) {
    var lessonTitle = button.getAttribute('data-title');
    var lessonLanguage = button.getAttribute('language');
    var messageContainer = document.getElementById('message-container');

    fetch('aggiungi_lezione.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title: lessonTitle,
            language: lessonLanguage,
        }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore nella richiesta AJAX');
            }
            return response.text(); // Converti la risposta in testo
        })
        .then(data => {
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.error) {
                    throw new Error(jsonData.error);
                }
                showMessage(jsonData.message, 'success');
            } catch (error) {
                console.error('Errore nel parsing della risposta JSON:', error.message);
            }
        })
        .catch(error => {
            console.error('Si è verificato un errore durante la richiesta AJAX:', error.message);
            // Gestione dell'errore lato client
        });

    // Funzione per mostrare il messaggio
    function showMessage(message, type) {
        var messageElement = document.createElement('div');
        messageElement.textContent = message;
        messageElement.classList.add('message', type);

        messageContainer.innerHTML = ''; // Pulisce eventuali messaggi precedenti
        messageContainer.appendChild(messageElement);
    }
}
