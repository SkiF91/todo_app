<div class="content-center" style="max-width: 600px;">
  <?= render_flashes(); ?>
  <form action="/todo.php?id=<?= $todo ? $todo->id : '' ?>" method="post">
    <p>
      <input type="text" name="name" value="<?= $todo ? $todo->name : '' ?>" placeholder="Введите имя списка">
      <input type="submit" value="<?= $todo ? 'Сохранить' : 'Создать' ?>">
    </p>
  </form>
</div>

<?php
if (!$todo) { exit; }
?>

<section class="todoapp">
  <header class="header">
    <input class="new-todo" placeholder="Чего, собственно, будем делать то ?" autofocus>
  </header>
  <!-- This section should be hidden by default and shown when there are todos -->
  <section class="main">
    <input class="toggle-all" type="checkbox">
    <ul class="todo-list">
      <!-- These are here just to show the structure of the list items -->
      <!-- List items should get the class `editing` when editing and `completed` when marked as completed -->
      <li class="completed">
        <div class="view">
          <input class="toggle" type="checkbox" checked>
          <label>Taste JavaScript</label>
          <button class="destroy"></button>
        </div>
        <input class="edit" value="Create a TodoMVC template">
      </li>
      <li>
        <div class="view">
          <input class="toggle" type="checkbox">
          <label>Buy a unicorn</label>
          <button class="destroy"></button>
        </div>
        <input class="edit" value="Rule the web">
      </li>
    </ul>
  </section>
  <!-- This footer should hidden by default and shown when there are todos -->
  <footer class="footer">
    <!-- This should be `0 items left` by default -->
    <span class="todo-count"><strong>0</strong> осталось</span>
    <!-- Remove this if you don't implement routing -->
    <ul class="filters">
      <li>
        <a class="selected" href="#" data-type="all">Все</a>
      </li>
      <li>
        <a href="#" data-type="active">Активные</a>
      </li>
      <li>
        <a href="#" data-type="completed">Завершенные</a>
      </li>
    </ul>
    <!-- Hidden if no completed items are left ↓ -->
    <button class="clear-completed">Удалить завершенные</button>
  </footer>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    $('.todoapp').todo();
  });
</script>