<?php

  // Avoid direct requests
  defined('RESTPATH') or exit(1);
  
  /**
   * Handles all GET requests
   * @return bool Always TRUE
   */
  function handle_get() {
    $user_ref = get_req_query('users');

    try {
      $db = new_db_instance();

      // If no value
      if (empty($user_ref)) {
        $offset = get_req_query('offset', 0);
        Output::print_data($db->get_users_list($offset));
      }

      // If value is set
      else {
        $user_data = is_numeric($user_ref) ? $db->get_user($user_ref) : $db->get_user_by_name($user_ref);

        if (is_null($user_data)) {
          Output::print_error('NOT_FOUND', 404);
        }

        Output::print_data($user_data);
      }
    } finally {
      $db = null;
    }
  }
