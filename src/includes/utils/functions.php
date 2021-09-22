<?php

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
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
  }

  /**
   * Returns current logged user name
   * @return string
   */
  function get_user_photo() {
    return isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : '/assets/img/nobody.svg';
  }


  /**
   * Returns user data from DB
   * @param integer $user_id
   * @return array|null
   */
  function get_user_data($user_id) {
    try {
      $db = new Database(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PWD);
      $user_data = $db->get_user($user_id);
      unset($user_data['user_password']);
      return $user_data;
    } finally {
      $db = null;
    }

    return null;
  }

  /**
   * Returns forum data from DB
   * @param string $forum_name
   * @return array|null
   */
  function get_forum_data($forum_name) {
    try {
      $db = new Database(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PWD);
      return $db->get_forum_by_name($forum_name);
    } finally {
      $db = null;
    }

    return null;
  }

  /**
   * Returns post data from DB
   * @param integer $post_id
   * @return array|null
   */
  function get_post_data($post_id) {
    try {
      $db = new Database(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PWD);
      return $db->get_post($post_id);
    } finally {
      $db = null;
    }

    return null;
  }

  /**
   * Prints out the page title
   * @return string
   */
  function print_page_title() {
    global $PAGE_TITLE;
    return isset($PAGE_TITLE) ? $PAGE_TITLE . ' - twebbit' : 'twebbit: the front page (clone) of the internet';
  }

  /**
   * Returns relative date
   * @param integer $unix_date
   * @return string
   */
  function get_relative_date($unix_date) {
    $seconds = UTIME - $unix_date;
    
    $interval = floor($seconds / 31536000);
    if ($interval > 1) return $interval . ' year(s)';

    $interval = floor($seconds / 2592000);
    if ($interval > 1) return $interval . ' month(s)';

    $interval = floor($seconds / 86400);
    if ($interval > 1) return $interval . ' day(s)';

    $interval = floor($seconds / 3600);
    if ($interval > 1) return $interval . ' hour(s)';

    $interval = floor($seconds / 60);
    if ($interval > 1) return $interval . ' minute(s)';

    return floor($interval) . ' second(s)';
  }

  /**
   * Returns number in shortdate version
   * @param integer $num
   * @return string
   */
  function short_number($num) {
    $i = 0;

    while ($num >= 1000) {
      $num = $num / 1000;
      $i++;
    }

    return $num . ['', 'k', 'M', 'B', 'T'][$i];
  }

  /**
   * Adds a JS script to footer
   * @param string $src
   * @return integer
   */
  function add_footer_script($src) {
    global $SCRIPTS;
    return array_push($SCRIPTS, $src);
  }

  /**
   * Returns HTML version of JS scripts list
   * @return string
   */
  function print_footer_scripts() {
    global $SCRIPTS;

    $SCRIPTS = array_unique($SCRIPTS);
    $to_return = '';

    foreach ($SCRIPTS as $script) {
      $to_return .= "<script src=\"$script\"></script>" . PHP_EOL;
    }

    return $to_return;
  }