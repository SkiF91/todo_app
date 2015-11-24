<div class="main-block content-center">
  <?php echo render_flashes(); ?>

  <form action="/login.php" method="post">
    <p>
      <input type="text" name="login" id="login" placeholder="Введите логин" autofocus required value="<?= isset($_POST['login']) ? $_POST['login'] : ''; ?>">
    </p>
    <p>
      <input type="password" name="password" id="password" placeholder="Введите пароль" required>
    </p>
    <p class="H">
      <a href="/register.php" class="text-middle">Регистрация</a>
      <input class="R" type="submit" value="Войти">
    </p>
  </form>
</div>