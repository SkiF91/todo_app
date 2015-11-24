<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('lib/active_array.php');
require_once('lib/session.php');
require_once('lib/db_connection.php');
require_once('lib/custom_vars.php');
require_once('lib/view_template.php');

CustomVars::$SESSION = Session::getInstance();
CustomVars::$DB = new DbConnection();
CustomVars::$current_user = extract_current_user();

function extract_current_user() {
  if (!CustomVars::$SESSION->user_id ||
      !CustomVars::$SESSION->user_session ||
      !isset($_COOKIE['PHPSESSID']) ||
      !isset($_SERVER['REMOTE_ADDR'])) {
    return false;
  }
  if (CustomVars::$SESSION->user_session != $_COOKIE['PHPSESSID'] || CustomVars::$SESSION->user_remote_addr != $_SERVER['REMOTE_ADDR']) {
    return false;
  }

  return CustomVars::$DB->find_user_by_id(CustomVars::$SESSION->user_id);
}

function random_salt() {
  return substr(sha1(mt_rand()), 0, 22);
}
function myhash($password, $salt) {
  return $salt . sha1($password . '\3\6\4');
}
function check_password($hash, $password) {
  $full_salt = substr($hash, 0, 22);
  $new_hash = myhash($password, $full_salt);
  return ($hash == $new_hash);
}

function array_to_li($arr) {
  if (!$arr || count($arr) == 0) { return; }
  $html = '<ul>';
  foreach ($arr as &$it) {
    $html .= "<li>$it</li>";
  }
  return $html . '</ul>';
}
function render_flashes() {
  if (!CustomVars::$SESSION->flash) {
    return '';
  }
  $html = '';
  foreach (CustomVars::$SESSION->flash as $k => $v) {
    $html .= "<div class='flash $k'>$v</div>";
  }
  CustomVars::$SESSION->flash = [];
  return $html;
}

function redirect_to_login_if_needed() {
  if (!CustomVars::$current_user) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
    exit;
  }
}

function render_paginator($data) {
  $max_page = ceil($data['count'] / 10);

  $page = $data['page'] > $max_page ? $max_page : $data['page'];

  $totals = '(' . ((($page - 1) * 10) + 1) . ' &mdash; ' . ($page * 10 > $data['count'] ? $data['count'] : $page * 10) . '/' . $data['count'] . ')';
  if ($max_page == 1) { return $totals; }
  $html = '';
  if ($page - 1 > 0) {
    $html .= '<a href="?page=' . ($page - 1) . '">&larr;</a>';
  }
  $available_pages = [];
  if ($page - 1 > 1) {
    $available_pages[] = 1;
  }
  for ($sch = $page - 1; $sch < $page + 2; $sch++) {
    if ($sch > 0 && $max_page >= $sch) {
      $available_pages[] = $sch;
    }
  }
  if ($max_page > $page + 2) {
    $available_pages[] = $max_page;
  }

  for ($sch = 0; $sch < count($available_pages); $sch ++) {
    if ($sch > 0 && $available_pages[$sch - 1] + 1 != $available_pages[$sch]) {
      $html .= '<span class="spacer">...</span>';
    }
    if ($available_pages[$sch] == $page) {
      $html .= " <span class='pg-sel'>$available_pages[$sch]</span>";
    } else {
      $html .= " <a href='?page=$available_pages[$sch]'>$available_pages[$sch]</a>";
    }
  }
  if ($page < $max_page) {
    $html .= ' <a href="?page=' . ($page + 1) . '">&rarr;</a>';
  }
  return "<div class='paginator'>$html <span class='pg-totals'>$totals</span></div>";
}