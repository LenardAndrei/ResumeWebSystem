<?php
    $host = "localhost";
    $port = "5432";
    $dbname = "resume_auth";
    $user = "postgres";
    $password = "Panganiban123!";

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
?>