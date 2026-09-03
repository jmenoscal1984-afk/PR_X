<?php
$host = 'db.tvmaztgcmxjkqrlijvhz.supabase.co'; // Mantén tu host principal
$port = '6543';                                // Puerto del pooler para evitar bloqueos
$dbname = 'postgres';
$user = 'postgres';                            // Regresamos al usuario simple 'postgres'
$password = 'B?7SrK44+?^?838';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    die("ERROR REAL DE SUPABASE: " . $e->getMessage());
}