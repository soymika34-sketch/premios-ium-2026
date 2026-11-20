<?php
$raiz = '';
require __DIR__ . '/includes/funciones.php';
if (usuario_actual()) { header('Location: pages/inicio.php'); exit; }
$cargos = ['Alumno', 'Docente', 'Administrativo'];
$errores = [];
$nombre = trim($_POST['nombre'] ?? '');
$cargo = $_POST['cargo'] ?? 'Alumno';
$correo = strtolower(trim($_POST['correo'] ?? ''));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';
    if (!csrf_valido()) $errores[] = 'Sesión expirada, intenta de nuevo.';
    if ($nombre === '' || mb_strlen($nombre) > 50) $errores[] = 'Escribe tu nombre (máximo 50 caracteres).';
    if (!in_array($cargo, $cargos, true)) $errores[] = 'Elige un cargo válido.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || mb_strlen($correo) > 100) $errores[] = 'Escribe un correo válido.';
    if (strlen($pass) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    if ($pass !== $pass2) $errores[] = 'Las contraseñas no coinciden.';
    if (!$errores) {
        try {
            $q = db()->prepare('INSERT INTO usuarios (nombre, cargo, correo, password_hash) VALUES (?,?,?,?)');
            $q->execute([$nombre, $cargo, $correo, password_hash($pass, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => (int)db()->lastInsertId(), 'nombre' => $nombre, 'cargo' => $cargo];
            header('Location: pages/inicio.php'); exit;
        } catch (PDOException $e) {
            $errores[] = $e->getCode() === '23000' ? 'Ese correo ya está registrado.' : 'No se pudo crear la cuenta, intenta más tarde.';
        }
    }
}
$titulo = 'REGISTRO'; $menu = false;
require __DIR__ . '/includes/header.php';
?>
    <div class="cinta">★ REGISTRO ★</div>
    <form class="formulario sin-icono" method="POST" action="registro.php">
        <?= csrf_campo() ?>
        <?php foreach ($errores as $e): ?><div class="mensaje error"><?= h($e) ?></div><?php endforeach; ?>
        <label class="campo"><span>👤</span><input type="text" name="nombre" placeholder="Nombre completo" maxlength="50" required value="<?= h($nombre) ?>"></label>
        <label class="campo"><span>🎓</span>
            <select name="cargo"><?php foreach ($cargos as $c): ?><option<?= $c === $cargo ? ' selected' : '' ?>><?= h($c) ?></option><?php endforeach; ?></select>
        </label>
        <label class="campo"><span>✉️</span><input type="email" name="correo" placeholder="Correo electrónico" maxlength="100" required value="<?= h($correo) ?>"></label>
        <label class="campo"><span>🔒</span><input type="password" name="password" placeholder="Contraseña (mín. 6)" minlength="6" required autocomplete="new-password"></label>
        <label class="campo"><span>🔒</span><input type="password" name="password2" placeholder="Repite la contraseña" minlength="6" required autocomplete="new-password"></label>
        <button class="btn" type="submit">Crear cuenta ➜</button>
        <div class="dato">¿Ya tienes cuenta? <a class="enlace" href="index.php"><b>Inicia sesión</b></a></div>
    </form>
<?php require __DIR__ . '/includes/footer.php'; ?>
