<?php
require_once('init.php');

redirect_to_login_if_needed();

$id = null;
if (isset($_POST['id']) && $_POST['id']) { $id = $_POST['id']; }
else if (isset($_GET['id']) && $_GET['id']) { $id = $_GET['id']; }
$todo = null;
if ($id) {
  $todo = CustomVars::$DB->find_todo_by_id($id);
}
if ($todo && CustomVars::$current_user->id != $todo->user_id) {
  CustomVars::$SESSION->flash->error = 'У вас нет прав для посещения этой страницы';
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['_method']) && $_POST['_method'] == 'delete') {
  CustomVars::$DB->delete_todo_by_id($todo->id);
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/?page=' . $page);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

}