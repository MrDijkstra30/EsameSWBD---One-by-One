<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Errore di Registrazione</title>
    <style>
        body {
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h1 {
            margin: 0 0 20px 0;
            font-size: 24px;
            color: #333;
        }

        .back-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            /* Aggiungi uno spazio tra il testo e il pulsante */
        }

        .back-container p {
            margin: 0;
            font-size: 18px;
            color: #666;
        }

        .back-container button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #FF5733;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-container button:hover {
            background-color: #c74422;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Errore nella registrazione!</h1>
        <div class="back-container">
            <p>Torna </p>
            <button onclick="window.location.href='http://localhost/esameSWBD/Authentification/registrati.php'">Indietro</button>
        </div>

    </div>
</body>

</html>