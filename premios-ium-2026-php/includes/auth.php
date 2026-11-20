<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
function usuario_actual(): ?array { return $_SESSION['usuario'] ?? null; }
function exigir_login(string $raiz): void {
    if (!usuario_actual()) { header('Location: ' . $raiz . 'index.php'); exit; }
}
