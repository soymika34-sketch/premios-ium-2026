<?php
$raiz = '';
require __DIR__ . '/includes/funciones.php';
if (usuario_actual()) { header('Location: pages/inicio.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = strtolower(trim($_POST['correo'] ?? ''));
    $pass = $_POST['password'] ?? '';
    if (!csrf_valido()) {
        $error = 'Sesión expirada, intenta de nuevo.';
    } else {
        $q = db()->prepare('SELECT id_usuario, nombre, cargo, password_hash FROM usuarios WHERE correo = ?');
        $q->execute([$correo]);
        $u = $q->fetch();
        if ($u && password_verify($pass, $u['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => (int)$u['id_usuario'], 'nombre' => $u['nombre'], 'cargo' => $u['cargo']];
            header('Location: pages/inicio.php'); exit;
        }
        $error = 'Correo o contraseña incorrectos';
    }
}
$titulo = 'INICIO DE SESIÓN'; $menu = false;
require __DIR__ . '/includes/header.php';
?>
    <div class="cinta">★ INICIO DE SESIÓN ★</div>
    <div class="contenido">
        <div class="mascota"><img src="img/leonium.png" alt="León IUM con trofeo" style="width:360px;max-width:90%"></div>
        <form class="formulario" method="POST" action="index.php">
            <div class="icono-user">👤</div>
            <?= csrf_campo() ?>
            <?php if ($error): ?><div class="mensaje error"><?= h($error) ?></div><?php endif; ?>
            <label class="campo"><span>✉️</span><input type="email" name="correo" placeholder="Correo" required autocomplete="email" value="<?= h($_POST['correo'] ?? '') ?>"></label>
            <label class="campo"><span>🔒</span><input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password"></label>
            <button class="btn" type="submit">Ingresar ➜</button>
            <div class="dato">¿No tienes cuenta? <a class="enlace" href="registro.php"><b>Regístrate aquí</b></a></div>
        </form>
    </div>
    <div class="frase-final" style="margin-top:20px">¡Celebramos<br>tu esfuerzo!</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
