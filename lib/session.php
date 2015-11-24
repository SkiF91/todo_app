<?php
class Session {
  const SESSION_LIFETIME = 1800; // 30m
  const SESSION_STARTED = TRUE;
  const SESSION_NOT_STARTED = FALSE;
  private $session_state = self::SESSION_NOT_STARTED;

  private static $instance;


  private function __construct() {}

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self;
    }

    self::$instance->start_session();

    return self::$instance;
  }

  public function start_session() {
    if ($this->session_state == self::SESSION_NOT_STARTED) {
      $this->session_state = session_start();
    }

    if (self::$instance->updated_at && (time() - self::$instance->updated_at) > self::SESSION_LIFETIME) {
      session_unset();
    }

    self::$instance->updated_at = time();

    if (!self::$instance->flash) {
      self::$instance->flash = [];
    }

    return $this->session_state;
  }

  public function __set($name, $value) {
    $_SESSION[$name] = $value;
  }

  public function __get($name) {
    if (isset($_SESSION[$name])) {
      if (is_array($_SESSION[$name])) {
        $property = new ActiveArray($_SESSION[$name]);
      } else {
        $property = $_SESSION[$name];
      }
      return $property;
    }
  }

  public function __isset($name) {
    return isset($_SESSION[$name]);
  }


  public function __unset($name) {
    unset($_SESSION[$name]);
  }

  public function destroy() {
    if ($this->session_state == self::SESSION_STARTED) {
      session_unset();
      $this->session_state = !session_destroy();
      return !$this->session_state;
    }

    return FALSE;
  }
}