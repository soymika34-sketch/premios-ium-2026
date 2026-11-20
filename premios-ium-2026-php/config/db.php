<?php
// Configuración (XAMPP por defecto: root sin contraseña)
const DB_HOST = 'localhost';
const DB_NAME = 'premios_ium';
const DB_USER = 'root';
const DB_PASS = '';
const MAX_VOTOS_CATEGORIA = 2;   // votos máximos por usuario EN CADA categoría

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            exit('No se pudo conectar a la base de datos. Revisa que MySQL esté encendido y que hayas importado sql/premios_ium.sql.');
        }
    }
    return $pdo;
}
