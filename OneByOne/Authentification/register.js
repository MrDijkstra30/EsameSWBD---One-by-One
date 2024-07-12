document.addEventListener("DOMContentLoaded", handler);

function handler() {
    const form = document.getElementById('regForm');
    form.addEventListener('submit', async function (event) { await messaggio(event); });

    async function messaggio(event) {
        event.preventDefault(); // Evita l'invio del form per ora

        // Controlli sui campi di input
        const fields = [
            { id: 'nome', errorId: 'nomeError', name: 'Nome' },
            { id: 'cognome', errorId: 'cognomeError', name: 'Cognome' },
            { id: 'email', errorId: 'emailError', name: 'E-mail', validate: isValidEmail },
            { id: 'username', errorId: 'usernameError', name: 'Username', validate: isValidUser },
            { id: 'password', errorId: 'passwordError', name: 'Password', validate: isValidPsw }
        ];

        let isValid = true;

        // Reset dei messaggi di errore
        fields.forEach(field => {
            const errorElement = document.getElementById(field.errorId);
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        });

        for (const field of fields) {
            const input = document.getElementById(field.id);
            const value = input.value;
            const errorElement = document.getElementById(field.errorId);

            if (value.length > 40) {
                errorElement.textContent = `${field.name} non può essere più lungo di 40 caratteri.`;
                errorElement.style.display = 'block';
                isValid = false;
            }

            if (field.validate) {
                const valid = await field.validate(value);
                if (!valid) {
                    errorElement.textContent = getErrorMessage(field.name);
                    errorElement.style.display = 'block';
                    isValid = false;
                }
            }
        }

        if (isValid) {
            // Se tutto è valido, puoi procedere con l'invio del form
            form.submit();
        }
    }

    function isValidPsw(password) {
        // Deve contenere almeno una lettera maiuscola e un numero
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        return hasUpperCase && hasNumber;
    }

    function isValidEmail(email) {
        // Verifica che l'email termini con @gmail.com, @outlook.com o @libero.it
        const validDomains = ['@gmail.com', '@outlook.com', '@libero.it'];
        return validDomains.some(domain => email.endsWith(domain));
    }

    async function isValidUser(username) {
        const response = await fetch(`isValidUser.php?username=${username}`);
        const data = await response.json();
        return !data.exists;
    }

    function getErrorMessage(fieldName) {
        if (fieldName === 'Password') {
            return 'Il campo Password deve contenere almeno una lettera maiuscola e un numero.';
        } else if (fieldName === 'E-mail') {
            return 'L\'email deve essere @gmail.com, @outlook.com o @libero.it.';
        } else if (fieldName === 'Username') {
            return 'L\'username è già in uso';
        } else {
            return `${fieldName} non è valido.`;
        }
    }
}
