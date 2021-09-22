<?php

  // Avoid direct requests
  defined('RESTPATH') or exit(1);

  /**
   * Handles all GET requests
   */
  function handle_get() {
    // Checks if user is logged in
    check_loggin_or_die();

    Output::print_data(['user_id' => get_user_id(), 'user_name' => get_user_name()]);
  }

  /**
   * Handles all POST requests
   */
  function handle_post() {
    // Read request params
    $req_type = get_req_query('account');
    $user_name = get_req_body('user_name');
    $user_pwd  = get_req_body('user_pwd');

    // If already logged in
    if (get_user_id() > 0) {
      Output::print_error('ALREADY_LOGGED_IN', 409);
    }

    // Check if all required parameters are present
    if (!$user_name || !$user_pwd) {
      Output::print_error('BAD_REQUEST', 400);
    }

    try {
      // Start DB class instance
      $db = new_db_instance();
      $user_data = $db->get_user_by_name($user_name);
      $user_sha_pwd = sha1($user_pwd);

      // If login request
      if ($req_type == 'login') {
        if ($user_data == null || $user_data['user_password'] != $user_sha_pwd) {
          Output::print_error('USER_NOT_EXIST', 404);
        }

        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['user_name'] = $user_data['user_name'];
        $_SESSION['user_photo'] = $user_data['user_photo'];
        Output::print_data(['user_id' => $user_data['user_id'], 'user_name' => $user_data['user_name']]);
      }

      // If register request
      else if ($req_type == 'register') {
        // Check the existence of the user
        if ($user_data != null) {
          Output::print_error('USER_EXIST', 409);
        }

        // Read optional param
        $user_photo = get_req_file('user_photo');
        $ul_done = false;

        // Initialize Uploader class
        $uploader = new Uploader(ABSPATH . 'uploads/users/');
        $uploader->set_allowed_exts('png', 'jpg', 'jpeg', 'gif', 'svg');

        // If user uses a profile photo, upload it
        if ($user_photo != false) {
          $ul_done = $uploader->upload_file($user_photo, $user_name);
          if (!$ul_done) {
            Output::print_error($uploader->get_last_upload_msg());
          }
        }

        $user_photo_uri = $ul_done ? '/uploads/users/' . $uploader->get_last_upload_filename() : '/assets/img/nobody.svg';
        $db_result = $db->create_user($user_name, $user_sha_pwd, $user_photo_uri);

        if ($db_result == -1) {
          $uploader->del_last_upload_file();
          Output::print_error('DB_ERROR');
        }

        $_SESSION['user_id'] = $db_result;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_photo'] = $user_photo_uri;
        Output::print_data(['user_id' => $db_result, 'user_name' => $user_name]);
      }
    } finally {
      $db = null;
    }
  }

  /**
   * Handles all DELETE requests
   */
  function handle_delete() {
    session_unset();
    session_destroy();
    Output::print_data('USER_LOGOUT_OK');
  }
