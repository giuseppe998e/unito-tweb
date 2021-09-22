<?php

  // Avoid direct requests
  defined('RESTPATH') or exit(1);

  /**
   * Handles all GET requests
   */
  function handle_get() {
    $post_ref = get_req_query('posts');

    try {
      $db = new_db_instance();

      // If number
      if (is_numeric($post_ref)) {
        $post_data = $db->get_post($post_ref);

        if (is_null($post_data)) {
          Output::print_error('NOT_FOUND', 404);
        }
        Output::print_data($data);
      }

      // If string
      else {
        $offset = get_req_query('offset', 0);

        switch($post_ref) {
          case 'home':
            $latest_posts = $db->get_latest_posts($offset);
            if (!is_null($latest_posts)) {
              Output::print_data($latest_posts);
            }
          break;
          case 'forum':
            $forum_name = get_req_query('n');
            if (is_string($forum_name)) {
              $forum_posts = $db->get_forum_posts($forum_name, $offset);
              if (!is_null($forum_posts)) {
                Output::print_data($forum_posts);
              }
            }
          break;
          case 'user':
            $user_id = get_req_query('id');
            if (is_numeric($user_id)) {
              $user_posts = $db->get_user_posts($user_id, $offset);
              if (!is_null($user_posts)) {
                Output::print_data($user_posts);
              }
            }
          break;
          default:
            Output::print_error('BAD_REQUEST', 400);
          break;
        }
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

    $forum_id = get_req_body('forum_id');
    $user_id = get_user_id();
    $post_title = get_req_body('post_title', null);
    $post_content = get_req_body('post_content', null);

    // If required params are missing
    if (!is_numeric($forum_id) || empty($post_title)) {
      Output::print_error('BAD_REQUEST', 400);
    }

    try {
      $db = new_db_instance();
      $result = $db->create_post($forum_id, $user_id, $post_title, $post_content);

      if ($result) {
        Output::print_data(null);
      }
    } finally {
      $db = null;
    }
  }

  /**
   * Handles all PUT requests
   */
  function handle_put() {
    // Checks if user is logged in
    check_loggin_or_die();

    $post_id = get_req_query('posts');
    $user_id = get_user_id();
    $is_like = get_req_query('is_like', 1);
    
    // If required params are missing
    if (!is_numeric($post_id)) {
      Output::print_error('BAD_REQUEST', 400);
    }

    try {
      $db = new_db_instance();
      $result = $is_like == 1 ? $db->like_post($post_id, $user_id) : $db->dislike_post($post_id, $user_id);

      if ($result) {
        Output::print_data(null);
      }
    } finally {
      $db = null;
    }
  }
