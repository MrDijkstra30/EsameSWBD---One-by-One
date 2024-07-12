<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
session_destroy();
$_SESSION['is_logged_in'] = false;
$_SESSION = [];
header("Location: ../index.php");
exit;
