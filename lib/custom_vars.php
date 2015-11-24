<?php
  require_once('lib/db_connection.php');
  final class CustomVars {
    public static $vars = [];
    public static $DB = null;
    public static $SESSION = null;
    public static $current_user = null;
  }