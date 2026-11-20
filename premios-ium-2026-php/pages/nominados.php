<?php
$raiz = '../';
require __DIR__ . '/../includes/funciones.php';
exigir_login($raiz);
$noms = db()->query(
    'SELECT p.*, c.nombre AS categoria FROM participante p
     JOIN categorias c ON c.id_categoria = p.id_categoria
     ORDER BY c.id_categoria, p.id_nominado')->fetchAll();
$titulo = 'NOMINADOS'; $activo = 'nominados'; $menu = true;
require __DIR__ . '/../includes/header.php';
?>
    <div class="cinta">★ NOMINADOS ★</div>
<?php if (!$noms): ?><p class="vacio">Aún no hay nominados registrados.</p><?php endif; ?>
    <div class="grid tres">
<?php foreach ($noms as $n):
    $foto = $n['imagen_participante'] ? '<img src="' . h($raiz . $n['imagen_participante']) . '" alt="Foto de ' . h($n['nombre_participante']) . '">' : '👤'; ?>
        <a class="tarjeta" href="categoria.php?id=<?= (int)$n['id_categoria'] ?>">
            <div class="etiqueta">★ <?= h($n['categoria']) ?></div>
            <div class="cuerpo"><div class="foto"><?= $foto ?></div><p><b><?= h($n['nombre_participante']) ?></b><br><?= h($n['nombre_trabajo']) ?></p></div>
            <span class="estrella">— ★ —</span>
        </a>
<?php endforeach; ?>
    </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
