<?php
$raiz = '../';
require __DIR__ . '/../includes/funciones.php';
exigir_login($raiz);
$titulo = 'INICIO'; $activo = 'inicio'; $menu = true;
require __DIR__ . '/../includes/header.php';
?>
    <div class="frase">"Premios IUM creados en 2026 para premiar los mejores trabajos realizados durante el ciclo escolar"</div>
    <div class="contador">Puedes votar hasta <b><?= MAX_VOTOS_CATEGORIA ?></b> veces en cada categoría · Votos emitidos: <b><?= count(votos_usuario(usuario_actual()['id'])) ?></b></div>
    <div class="mascota"><img src="../img/leonium.png" alt="León IUM con trofeo"></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
