<?php
// Ne conectăm la Redis pentru a găsi sesiunea ta curentă
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:6379');
session_start();

// Distrugem complet sesiunea (uităm că ești logată)
session_destroy();

// Te trimitem înapoi pe pagina principală
header("Location: index.php");
exit();
?>