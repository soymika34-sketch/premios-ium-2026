<?php
$raiz = '../';
require __DIR__ . '/../includes/funciones.php';
exigir_login($raiz);
$id = (int)($_GET['id'] ?? 0);
$q = db()->prepare('SELECT id_categoria, nombre FROM categorias WHERE id_categoria = ?');
$q->execute([$id]);
$cat = $q->fetch();
if (!$cat) { header('Location: categorias.php'); exit; }
$q = db()->prepare('SELECT * FROM participante WHERE id_categoria = ? ORDER BY id_nominado');
$q->execute([$id]);
$noms = $q->fetchAll();
foreach ($noms as &$n) { $n['categoria'] = $cat['nombre']; }
unset($n);
$uid = usuario_actual()['id'];
$mis = votos_usuario($uid);
$votados = array_map('intval', array_column($mis, 'id_nominado'));
$usados = votos_en_categoria($mis, $id);
$puede = $usados < MAX_VOTOS_CATEGORIA;
$titulo = $cat['nombre']; $activo = 'categorias'; $menu = true;
require __DIR__ . '/../includes/header.php';
?>
    <div class="cinta">★ <?= h($cat['nombre']) ?> ★</div>
    <?php mostrar_flash(); ?>
    <?= contador_categoria($usados) ?>
<?php if (!$noms): ?><p class="vacio">Aún no hay nominados en esta categoría.</p><?php endif; ?>
    <div class="grid tres">
<?php foreach ($noms as $n) echo tarjeta_participante($n, $raiz, $votados, $puede); ?>
    </div>
    <a class="volver enlace" href="categorias.php">← Volver a categorías</a>
<?php require __DIR__ . '/../includes/footer.php'; ?>
