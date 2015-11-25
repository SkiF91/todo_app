<?php
require_once('init.php');

redirect_to_login_if_needed();

$id = null;
if (isset($_POST['id']) && $_POST['id']) { $id = $_POST['id']; }
else if (isset($_GET['id']) && $_GET['id']) { $id = $_GET['id']; }
$todo = null;
if ($id) {
  $todo = CustomVars::$DB->find_todo_by_id($id);

  if (!$todo) {
    CustomVars::$SESSION->notice->error = 'Список не найден';
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
  }
}
if ($todo && CustomVars::$current_user->id != $todo->user_id) {
  CustomVars::$SESSION->flash->error = 'У вас нет прав для посещения этой страницы';
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['_method']) && $_POST['_method'] == 'delete')) {
  CustomVars::$DB->delete_todo_by_id($todo->id);
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/?page=' . $page);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = isset($_POST['name']) ? $_POST['name'] : '';

  if (!preg_match('/^[a-zA-Z0-9\x{0430}-\x{044F}\x{0410}-\x{042F}\s]{3,50}$/u', $name)) {
    if (is_ajax()) {
      header("Content-Type: application/javascript");
      echo 'alert("Имя должно состоять только из букв и цифр и должно быть не короче 3 и не длиньше 50 символов");';
      exit;
    }
    CustomVars::$SESSION->flash->error = 'Имя должно состоять только из букв и цифр и должно быть не короче 3 и не длиньше 50 символов';
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/todo.php?id=' . $id);
    exit;
  }
  $items = isset($_POST['items']) && $_POST['items'] ? $_POST['items'] : null;
  $todo_id = CustomVars::$DB->create_or_update_todo($name, $todo, $items, CustomVars::$current_user);
  if (is_array($items)) {
    if (is_ajax()) {
      echo 'alert(' . join('<br>', $items) . ');';
      exit;
    }
    CustomVars::$SESSION->flash->error = array_to_li($items);
  }
  // ajax better.... but....
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/todo.php?id=' . $todo_id);

  exit;
}

$items = $todo ? CustomVars::$DB->find_todo_items_by_id($todo->id) : null;

ViewTemplate::$title = "todos &mdash; " . ($todo ? $todo->name : 'New ToDo');
ViewTemplate::$head = 'views/todo_head.php';
ViewTemplate::$body = 'views/todo_view.php';
include "views/layout.php";