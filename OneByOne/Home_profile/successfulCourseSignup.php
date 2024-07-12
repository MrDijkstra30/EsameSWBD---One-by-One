<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successo Registrazione</title>
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

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            /* Aggiungi uno spazio tra il testo e il pulsante */
        }

        .login-container p {
            margin: 0;
            font-size: 18px;
            color: #666;
        }

        .login-container button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Registrazione al corso avvenuta con successo!</h1>
        <div class="login-container">
            <p>Vai alla tua </p>
            <button onclick="window.location.href='http://localhost/esameSWBD/Home_Profile/homeCorsi.php'">Lista Corsi</button>
        </div>
    </div>
</body>

</html>