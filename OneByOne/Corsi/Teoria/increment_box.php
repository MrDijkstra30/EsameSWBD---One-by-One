<?php
// Avvia la sessione
session_start();

// Inizializza la variabile $boxToShow se non è già impostata
if (!isset($_SESSION['boxToShow'])) {
    $_SESSION['boxToShow'] = 0;
}

// Incrementa la variabile $boxToShow
$_SESSION['boxToShow']++;

// Restituisci il nuovo valore
echo $_SESSION['boxToShow'];
