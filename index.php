<?php
require_once('init.php');

if (!ViewTemplate::$rendered) {
  redirect_to_login_if_needed();

  ViewTemplate::$title = "todos &mdash; List";
  ViewTemplate::$body = __FILE__;
  include "views/layout.php";
  exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$data = CustomVars::$DB->paginated_todos(CustomVars::$current_user->id, $page);
?>

<div class="main-block content-center">
  <?= render_flashes(); ?>
  <div class="autoscroll">
    <?php
    if ($data['count'] == 0) {
      echo '<div class="nodata">Нет данных для отображения</div>';
    } else {
      echo '<table class="todos-list">';
      foreach($data['rows'] as $row) {
        echo '<tr>';
        echo "<td id='td-$row->id'><a href='/todo.php?id=$row->id'>$row->name</a></td><td><a href='/todo.php?id=$row->id&page=$page' data-remote='true' data-method='delete' class='fa fa-close del' title='Удалить'></a></td>";
        echo '</tr>';
      }
      echo '</table>';
    }
    ?>
  </div>
  <?= render_paginator($data); ?>
</div>
