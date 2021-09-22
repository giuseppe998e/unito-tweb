<?php

  // Avoid direct requests
  defined('RESTPATH') or exit(1);
  
  /**
   * Handles all GET requests
   * @return bool Always TRUE
   */
  function handle_get() {
    $forum_ref = get_req_query('forums');

    try {
      $db = new_db_instance();

      // If no value
      if (empty($forum_ref)) {
        $offset = get_req_query('offset', 0);
        Output::print_data($db->get_forums_list($offset));
      }

      // If value is set
      else {
        $forum_data = is_numeric($forum_ref) ? $db->get_forum($forum_ref) : $db->get_forum_by_name($forum_ref);

        if (is_null($forum_data)) {
          Output::print_error('NOT_FOUND', 404);
        }

        Output::print_data($forum_data);
      }
    } finally {
      $db = null;
    }
  }
