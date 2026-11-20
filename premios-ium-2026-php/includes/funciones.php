<?php
require_once __DIR__ . '/auth.php';

function h($t): string { return htmlspecialchars((string)$t, ENT_QUOTES, 'UTF-8'); }

// CSRF
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf'];
}
function csrf_campo(): string { return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">'; }
function csrf_valido(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// Mensajes
function flash(string $tipo, string $texto): void { $_SESSION['flash'] = [$tipo, $texto]; }
function mostrar_flash(): void {
    if (!empty($_SESSION['flash'])) {
        [$t, $m] = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="mensaje ' . h($t) . '" style="margin-top:20px">' . h($m) . '</div>';
    }
}

// Votos del usuario (con nombre de categoría y participante)
function votos_usuario(int $uid): array {
    $q = db()->prepare(
        'SELECT v.id_voto, v.id_nominado, p.id_categoria, p.nombre_participante, c.nombre AS categoria
         FROM votos v
         JOIN participante p ON p.id_nominado = v.id_nominado
         JOIN categorias c ON c.id_categoria = p.id_categoria
         WHERE v.id_usuario = ? ORDER BY v.fecha_voto');
    $q->execute([$uid]);
    return $q->fetchAll();
}

// Cuántos votos lleva el usuario en una categoría
function votos_en_categoria(array $mis, int $idCat): int {
    $n = 0;
    foreach ($mis as $v) { if ((int)$v['id_categoria'] === $idCat) $n++; }
    return $n;
}

function contador_categoria(int $usados): string {
    $rest = max(0, MAX_VOTOS_CATEGORIA - $usados);
    return '<div class="contador">En esta categoría has usado <b>' . $usados . '</b> de <b>' . MAX_VOTOS_CATEGORIA . '</b> votos · Te quedan <b>' . $rest . '</b></div>';
}

function tarjeta_participante(array $p, string $raiz, array $votados, bool $puedeVotar): string {
    $foto = $p['imagen_participante']
        ? '<img src="' . h($raiz . $p['imagen_participante']) . '" alt="Foto de ' . h($p['nombre_participante']) . '">'
        : '👤';
    $ya = in_array((int)$p['id_nominado'], $votados, true);
    $btn = $ya
        ? '<button class="btn" disabled>✔ Votado</button>'
        : '<button class="btn" type="submit"' . ($puedeVotar ? '' : ' disabled') . '>Votar ➜</button>';
    return '<form class="tarjeta fija" method="POST" action="' . h($raiz) . 'pages/procesar_voto.php">'
        . csrf_campo()
        . '<input type="hidden" name="id_nominado" value="' . (int)$p['id_nominado'] . '">'
        . '<div class="etiqueta">★ ' . h($p['categoria'] ?? 'NOMINADO') . '</div>'
        . '<div class="cuerpo"><div class="foto">' . $foto . '</div>'
        . '<p><b>' . h($p['nombre_participante']) . '</b><br>' . h($p['nombre_trabajo']) . '</p></div>'
        . $btn . '</form>';
}
