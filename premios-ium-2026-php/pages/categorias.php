<?php
$raiz = '../';
require __DIR__ . '/../includes/funciones.php';
exigir_login($raiz);
$cats = db()->query('SELECT id_categoria, nombre, imagen_categoria FROM categorias ORDER BY id_categoria')->fetchAll();
$titulo = 'CATEGORÍAS'; $activo = 'categorias'; $menu = true;
require __DIR__ . '/../includes/header.php';
?>
    <div class="cinta">★ CATEGORÍAS ★</div>
    <div class="grid dos">
<?php foreach ($cats as $c): ?>
        <a class="cat" href="categoria.php?id=<?= (int)$c['id_categoria'] ?>"><?= h($c['nombre']) ?> <span>★</span></a>
<?php endforeach; ?>
    </div>
    <div class="abajo"><img src="../img/leonium.png" alt="León IUM con trofeo"><div class="frase-final">¡Celebramos<br>tu esfuerzo!</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
