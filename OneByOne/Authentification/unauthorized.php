<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utente non autorizzato</title>
    <style>
        body {
            background-color: #0775ce;
            /* Blu leggermente chiaro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .message-box {
            background-color: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-size: 24px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            color: white;
            background-color: #0775ce;
            /* Nuovo colore */
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #055aae;
            /* Un blu più scuro al passaggio del mouse */
        }
    </style>
</head>

<body>
    <div class="message-box">
        <h3>Utente non autorizzato</h3>
        <p>Effettua l'accesso o registrati per accedere alla risorsa.</p>
        <a href="http://localhost/esameSWBD/" class="button">Capito!</a>
    </div>
</body>

</html>