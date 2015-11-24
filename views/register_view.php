<div class="main-block">
  <?php echo render_flashes(); ?>

  <form action="/register.php" method="post">
    <p>
      <input type="text" name="login" id="login" placeholder="Логин" autofocus value="<?= isset($_POST['login']) ? $_POST['login'] : ''; ?>">
    </p>
    <p>
      <input type="password" name="password" id="password" placeholder="Пароль">
    </p>
    <p>
      <input type="password" name="password_confirm" id="password_confirm" placeholder="Подтверждение пароля">
    </p>
    <p class="H">
      <a href="/login.php" class="text-middle">Аутентификация</a>
      <input class="R" type="submit" value="Зарегистрироваться">
    </p>
  </form>
</div>