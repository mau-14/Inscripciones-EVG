<form method="POST" action="index.php?controlador=auth&accion=login">
  <input type="text" name="usuario" placeholder="Usuario" required />
  <input type="password" name="password" placeholder="Contraseña" required />
  <button type="submit">Entrar</button>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
</form>
