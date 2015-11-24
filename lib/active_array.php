<?php
class ActiveArray implements ArrayAccess, Iterator {
  protected $array;

  public function __set($name, $value) {
    $this->array[$name] = $value;
  }

  public function __get($name) {
    if (is_array($this->array[$name]))
      return new self($this->array[$name]);
    else
      return $this->array[$name];
  }

  public function __construct(&$array) {
    $this->array = &$array;
  }

  public function offsetGet($offset) {
    return $this->array[$offset];
  }

  public function offsetSet($offset, $value) {
    if ($offset === '') $this->array[] = $value;
    else $this->array[$offset] = $value;
  }

  public function offsetUnset($offset) {
    unset($this->array[$offset]);
  }

  public function offsetExists($offset) {
    return isset($this->array[$offset]);
  }

  function rewind() {
    reset($this->array);
  }

  function current() {
    return current($this->array);
  }

  function key() {
    return key($this->array);
  }

  function next() {
    next($this->array);
  }

  function valid() {
    return $this->key() !== null;
  }
}