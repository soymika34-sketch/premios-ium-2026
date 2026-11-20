<?php
$raiz = '../';
require __DIR__ . '/../includes/funciones.php';
exigir_login($raiz);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
    flash('error', 'Solicitud no válida.');
    header('Location: votar.php'); exit;
}

$uid = usuario_actual()['id'];
$idNom = (int)($_POST['id_nominado'] ?? 0);
$pdo = db();

try {
    $pdo->beginTransaction();
    // Bloquea la fila del usuario para que dos clics simultáneos no se salten el límite
    $pdo->prepare('SELECT id_usuario FROM usuarios WHERE id_usuario = ? FOR UPDATE')->execute([$uid]);

    // Categoría del participante
    $q = $pdo->prepare('SELECT id_categoria FROM participante WHERE id_nominado = ?');
    $q->execute([$idNom]);
    $idCat = $q->fetchColumn();
    if ($idCat === false) {
        $pdo->rollBack();
        flash('error', 'Ese participante no existe.');
        header('Location: votar.php'); exit;
    }

    // Votos del usuario en ESA categoría
    $q = $pdo->prepare(
        'SELECT COUNT(*) FROM votos v
         JOIN participante p ON p.id_nominado = v.id_nominado
         WHERE v.id_usuario = ? AND p.id_categoria = ?');
    $q->execute([$uid, (int)$idCat]);
    if ((int)$q->fetchColumn() >= MAX_VOTOS_CATEGORIA) {
        $pdo->rollBack();
        flash('error', 'Ya usaste tus ' . MAX_VOTOS_CATEGORIA . ' votos en esta categoría.');
        header('Location: votar.php'); exit;
    }

    $pdo->prepare('INSERT INTO votos (id_usuario, id_nominado, fecha_voto) VALUES (?, ?, NOW())')
        ->execute([$uid, $idNom]);
    $pdo->commit();
    flash('ok', '¡Voto registrado!');
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('error', $e->getCode() === '23000' ? 'Ya votaste por este participante.' : 'No se pudo registrar el voto.');
}
header('Location: votar.php');
exit;
