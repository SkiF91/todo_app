<?php
require_once('init.php');

if (CustomVars::$current_user) {
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
  exit;
}

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
  $res = try_register();
  if (!$res) {
    CustomVars::$SESSION->flash->error = 'Unknown error occurred';
  } else if (is_array($res)) {
    CustomVars::$SESSION->flash->error = array_to_li($res);
  } else {
    CustomVars::$SESSION->flash->notice = 'Вы успешно зарегистрировались. Теперь вы можете зайти в систему под своим логином  паролем.';
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
    exit;
  }
}


function try_register() {
  $errors = [];
  if (!isset($_POST['login']) || !$_POST['login']) { $errors[] = 'Логин не может быть пустым'; }
  if (!isset($_POST['password']) || !$_POST['password']) { $errors[] = 'Пароль не может быть пустым'; }
  if (!isset($_POST['password_confirm']) || !$_POST['password_confirm']) { $errors[] = 'Подтверждение пароля не может быть пустым'; }
  if (!preg_match('/^\w{3,20}$/', $_POST['login'])) { $errors[] = 'Логин должен состоять из только из латинских букв и цифр и должен быть не короче 3 и не длиньше 20 символов'; }
  if (!preg_match('/^\w{6,20}$/', $_POST['password'])) { $errors[] = 'Пароль должен состоять из только из латинских букв и цифр и должен быть не короче 3 и не длиньше 20 символов'; }
  if ($_POST['password'] != $_POST['password_confirm']) { $errors[] = 'Пароль должен совпадать с подтверждением пароля'; }

  if (CustomVars::$DB->find_user_by_login($_POST['login'])) {
    $errors[] = 'Пользователь с таким логином уже существует';
  }

  if (count($errors) > 0) { return $errors; }

  return CustomVars::$DB->create_user($_POST['login'], myhash($_POST['password'], random_salt()));
}

ViewTemplate::$title = "todos &mdash; Register";
ViewTemplate::$body = 'views/register_view.php';
include "views/layout.php";