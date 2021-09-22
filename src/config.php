<?php

  // MariaDB/MySQL database config
  define('DB_HOST', '127.0.0.1');
  define('DB_PORT',  3306);
  define('DB_NAME', 'unito_tweb');
  define('DB_USER', 'root');
  define('DB_PWD' , 'pwd123');
  
  // -----------------------------
  // Do not edit from here on!

  /**
   * Script absolute path
   * @var string ABSPATH 
   */
  define('ABSPATH', dirname(__FILE__) . '/');

  /**
   * The "includes" directory absolute path
   * @var string INCPATH 
   */
  define('INCPATH', ABSPATH . 'includes/');
  
  /**
   * Unix time of execution
   * @var integer UTIME
   */
  define('UTIME', time());

  /**
   * Page scripts (with default values)
   * @var array $SCRIPTS
   */
  $SCRIPTS = array(
    '/assets/js/lib/jquery.min.js',
    '/assets/js/lib/bootstrap.min.js',
    '/assets/js/functions.js',
    '/assets/js/site.js'
  );
