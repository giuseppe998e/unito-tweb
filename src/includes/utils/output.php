<?php

class Output {
  /**
   * ...
   * @param mixed $data
   * @param int $code
   */
  public static function print_data($data, $code = 200) {
    self::send_headers($code);
    echo json_encode(['ok' => true, 'data' => $data]);
    exit(0);
  }

  /**
   * ...
   * @param string $msg
   * @param int $code
   */
  public static function print_error($msg, $code = 500) {
    self::send_headers($code);
    echo json_encode(['ok' => false, 'error' => ['code' => $code, 'message' => $msg]]);
    exit(1);
  }

  /**
   * ...
   * @param int $code
   */
  private static function send_headers($code) {
    http_response_code($code);

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
  }
}