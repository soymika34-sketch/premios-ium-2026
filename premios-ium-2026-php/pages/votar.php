<?php
$raiz = '../';
require __DIR__ . '/../includes/funciones.php';
exigir_login($raiz);
$uid = usuario_actual()['id'];
$mis = votos_usuario($uid);
$votados = array_map('intval', array_column($mis, 'id_nominado'));
$filas = db()->query(
    'SELECT p.*, c.nombre AS categoria FROM participante p
     JOIN categorias c ON c.id_categoria = p.id_categoria
     ORDER BY c.id_categoria, p.id_nominado')->fetchAll();
$porCat = [];
foreach ($filas as $f) {
    $porCat[(int)$f['id_categoria']]['nombre'] = $f['categoria'];
    $porCat[(int)$f['id_categoria']]['lista'][] = $f;
}
$titulo = 'VOTAR'; $activo = 'votar'; $menu = true;
require __DIR__ . '/../includes/header.php';
?>
    <div class="cinta">★ VOTAR ★</div>
    <?php mostrar_flash(); ?>
    <div class="contador">Puedes votar hasta <b><?= MAX_VOTOS_CATEGORIA ?></b> veces en cada categoría</div>
<?php if ($mis): ?>
    <ul class="lista-votos">
<?php foreach ($mis as $v): ?>
        <li>✔ <b><?= h($v['categoria']) ?></b>: <?= h($v['nombre_participante']) ?></li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php foreach ($porCat as $idCat => $info):
    $usados = votos_en_categoria($mis, $idCat);
    $puede = $usados < MAX_VOTOS_CATEGORIA; ?>
    <h2 class="subtitulo">★ <?= h($info['nombre']) ?> <small style="font-size:15px;color:#e8c96a">(<?= $usados ?>/<?= MAX_VOTOS_CATEGORIA ?> votos)</small></h2>
    <div class="grid tres">
<?php foreach ($info['lista'] as $n) echo tarjeta_participante($n, $raiz, $votados, $puede); ?>
    </div>
<?php endforeach; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
