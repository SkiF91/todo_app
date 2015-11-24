<?php

require_once('init.php');

if (CustomVars::$current_user) {
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
  exit;
}

const MAX_LOGIN_ATTEMPTS = 5;
const ATTEMPTS_EXCEED = 2;
const LOGIN_SUCCESS = 1;
const LOGIN_FAIL = 0;


if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
  $res = try_login();
  if ($res == LOGIN_SUCCESS) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
  } else if ($res == LOGIN_FAIL) {
    CustomVars::$SESSION->flash->error = 'Логин или пароль указаны не верно';
  } else if ($res == ATTEMPTS_EXCEED) {
    CustomVars::$SESSION->flash->error = 'Вы использовали все попытки для входа, аутентификация недоступна в течении 5 минут';
  }
}

function store_user($user_id) {
  if (!$user_id) { return; }
  CustomVars::$SESSION->user_session = $_COOKIE['PHPSESSID'];
  CustomVars::$SESSION->user_remote_addr = $_SERVER['REMOTE_ADDR'];
  CustomVars::$SESSION->user_id = $user_id;
}
function try_login() {
  if (!isset($_POST['login']) || !$_POST['login'] || !isset($_POST['password']) || !$_POST['password']) { return LOGIN_FAIL; }
  if (CustomVars::$DB->set_login_attempts($_POST['login']) > MAX_LOGIN_ATTEMPTS) {
    return ATTEMPTS_EXCEED;
  }
  $user = CustomVars::$DB->find_user_by_login($_POST['login']);
  if (!$user) { return LOGIN_FAIL; }

  if (check_password($user->password, $_POST['password'])) {
    store_user($user->id);
    return LOGIN_SUCCESS;
  } else {
    return LOGIN_FAIL;
  }
}

ViewTemplate::$title = "todos &mdash; Login";
ViewTemplate::$body = 'views/login_view.php';
include "views/layout.php";