<?php /* Variables: $titulo, $activo (opcional), $raiz, $menu (bool) */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($titulo) ?> - PREMIOS IUM 2026</title>
<link rel="stylesheet" href="<?= h($raiz) ?>css/estilos.css">
</head>
<body>
<div class="pagina">
    <div class="gala">★ GALA OFICIAL ESCOLAR ★</div>
    <h1 class="titulo">PREMIOS IUM<span>2026</span></h1>
<?php if (!empty($menu)): $items = ['inicio' => 'INICIO', 'categorias' => 'CATEGORÍAS', 'nominados' => 'NOMINADOS', 'votar' => 'VOTAR']; ?>
    <nav class="menu">
<?php foreach ($items as $id => $t): ?>
        <a href="<?= h($raiz) ?>pages/<?= $id ?>.php"<?= ($activo ?? '') === $id ? ' class="activo"' : '' ?>><?= $t ?></a>
<?php endforeach; ?>
        <a href="<?= h($raiz) ?>logout.php">SALIR (<?= h(explode(' ', usuario_actual()['nombre'])[0]) ?>)</a>
    </nav>
<?php endif; ?>
