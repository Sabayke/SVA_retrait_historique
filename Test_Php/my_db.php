<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=sva', 'root', '')
or die("Erreur dans la base de donnés");
?>