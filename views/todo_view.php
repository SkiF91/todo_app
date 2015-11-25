<form action="/todo.php?id=<?= $todo ? $todo->id : '' ?>" method="post"<?= $todo ? ' data-remote="true"' : ''?>>
  <div class="content-center" style="max-width: 600px;">
    <?= render_flashes(); ?>
    <p>
      <input type="text" name="name" value="<?= $todo ? $todo->name : '' ?>" placeholder="Введите имя списка">
      <input type="submit" value="<?= $todo ? 'Сохранить' : 'Создать' ?>">
    </p>
  </div>


<?php
if (!$todo) {
  echo '</form>';
  exit;
}
?>

  <section class="todoapp">
    <header class="header">
      <input class="new-todo" placeholder="Чего, собственно, будем делать то ?" autofocus>
    </header>
    <section class="main">
      <input class="toggle-all" type="checkbox">
      <ul class="todo-list">
        <!-- better to load via json, but.... -->
        <?php
          if ($items) {
            $ind = 0;
            foreach ($items as $row) {
              echo '<li' . ($row->completed == 1 ? ' class="completed"' : '') . '>';
              echo '<div class="view">';
              echo '<input class="toggle" type="hidden" value="0" name="items[' . $ind . '][completed]">';
              echo '<input class="toggle" type="checkbox" ' . ($row->completed == 1 ? 'checked' : '') . ' name="items[' . $ind . '][completed]">';
              echo "<label>$row->name</label>";
              echo '<button class="destroy"></button>';
              echo '</div>';
              echo '<input class="edit" value="' . $row->name . '" name="items[' . $ind . '][name]">';
              echo '</li>';
              $ind ++;
            }
          }
        ?>
      </ul>
    </section>

    <footer class="footer">
      <span class="todo-count"><strong>0</strong> осталось</span>
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
      <button class="clear-completed">Удалить завершенные</button>
    </footer>
  </section>
</form>

<script type="text/javascript">
  $(document).ready(function() {
    $('.todoapp').todo();
  });
</script>