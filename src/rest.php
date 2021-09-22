<?php
  // https://restfulapi.net/http-methods/
  // https://restapitutorial.com/httpstatuscodes.html

  // Load CORE scripts
  require_once('config.php');
  require_once(INCPATH . 'utils/rest-functions.php');
  require_once(INCPATH . 'utils/output.php');
  require_once(INCPATH . 'utils/uploader.php');
  require_once(INCPATH . 'utils/database.php');
  define('RESTPATH', INCPATH . 'rests/');

  // Start session manager
  ob_start();
  session_start();

  // Load the requested endpoint
  if (isset($_GET['account'])) {
    require_once(RESTPATH . 'account.php');
  }
  else if (isset($_GET['users'])) {
    require_once(RESTPATH . 'users.php');
  }
  else if (isset($_GET['forums'])) {
    require_once(RESTPATH . 'forums.php');
  }
  else if (isset($_GET['posts'])) {
    require_once(RESTPATH . 'posts.php');
  }
  else if (isset($_GET['comments'])) {
    require_once(RESTPATH . 'comments.php');
  }
  else {
    Output::print_error('BAD_REQUEST', 400);
  }

  // Handle request methods
  if ($_SERVER['REQUEST_METHOD'] != 'HEAD') {
    $fn = 'handle_' . strtolower($_SERVER['REQUEST_METHOD']);
    if (function_exists($fn)) {
      call_user_func($fn);
    } else {
      Output::print_error('NOT_IMPLEMENTED', 501);
    }
    // No response from enpoints results in a 500 Error
    Output::print_error('SERVER_ERROR');
  }
