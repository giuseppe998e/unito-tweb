<?php

  // Avoid direct requests
  defined('RESTPATH') or exit(1);

  /**
   * Handles all GET requests
   */
  function handle_get() {
    $post_id = get_req_query('comments');
    $offset = get_req_query('offset', 0);
    $limit = get_req_query('limit', 15);

    // If postID is not set
    if (!is_numeric($post_id)) {
      Output::print_error('BAD_REQUEST', 400);
    }

    try {
      $db = new_db_instance();
      $result = $db->get_post_comments($post_id, $offset, $limit);

      if (!is_null($result)) {
        Output::print_data($result);
      }
    } finally {
      $db = null;
    }
  }

  /**
   * Handles all POST requests
   */
  function handle_post() {
    // Checks if user is logged in
    check_loggin_or_die();

    $post_id = get_req_body('post_id');
    $user_id = get_user_id();

    $text = get_req_body('text', null);
    $text = strip_tags($text, '<br>');

    // If postID or text is not set
    if (!is_numeric($post_id) || empty($text)) {
      Output::print_error('BAD_REQUEST', 400);
    }

    try {
      $db = new_db_instance();
      $result = $db->add_post_comment($post_id, $user_id, $text);

      if ($result) {
        Output::print_data(null);
      }
    } finally {
      $db = null;
    }
  }