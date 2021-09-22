<?php

  /**
   * Checks if user is logged in or end execution
   * @return true
   */
  function check_loggin_or_die() {
    if (!isset($_SESSION['user_id'])) {
      Output::print_error('NOT_LOGGED_IN', 403);
    }
    return true;
  }

  /**
   * Returns current logged user ID
   * @return integer
   */
  function get_user_id() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : -1;
  }

  /**
   * Returns current logged user name
   * @return string
   */
  function get_user_name() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : false;
  }

  /**
   * Returns a new DB instance
   * @return object
   */
  function new_db_instance() {
    return new Database(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PWD); 
  }

  /**
   * Checks if $_GET[$name] is set and returns its value
   * else returns FALSE
   * @param string $name
   * @param mixed $def_value?
   * @return mixed
   */
  function get_req_query($name, $def_value = false) {
    return isset($_GET[$name]) ? $_GET[$name] : $def_value;
  }

  /**
   * Checks if $_POST[$name] is set and returns its value
   * else returns FALSE
   * @param string $name
   * @param mixed $def_value?
   * @return mixed
   */
  function get_req_body($name, $def_value = false) {
    return isset($_POST[$name]) ? $_POST[$name] : $def_value;
  }

  /**
   * Checks if $_FILES[$name] is set and returns its value
   * else returns FALSE
   * @param string $name
   * @param mixed $def_value?
   * @return mixed
   */
  function get_req_file($name, $def_value = false) {
    return isset($_FILES[$name]) ? $_FILES[$name] : $def_value;
  }