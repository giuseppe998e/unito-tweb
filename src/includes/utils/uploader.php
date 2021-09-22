<?php

class Uploader {
  /**
   * Upload destination path
   * @var string
   */
  private $dest_dir;

  /**
   * Accepted extensions (Empty list == No restrictions)
   * @var array
   */
  private $allowed_exts;

  /**
   * The maximum upload size
   * By default the value is retrieved from the PHP.ini file
   * @var integer
   */
  private $max_size;

  /**
   * Last upload status
   * @var string
   */
  private $last_upload_msg;

  /**
   * Last uploaded file name
   * @var string
   */
  private $last_upload_filename;

  /**
   * Uploader constructor
   * @param string $dir
   */
  public function __construct($dir = null) {
    $this->allowed_exts = [];
    $this->last_upload_msg = null;
    $this->last_upload_filename = null;
    $this->set_destination($dir);
    $this->max_size = self::file_upload_max_size();
  }

  /**
   * Sets upload directory
   * @param string $dir
   */
  public function set_destination($dir) {
    $this->dest_dir = $dir . (substr($dir, -1) != '/' ? '/' : '');
    if (!is_dir($this->dest_dir)) {
      return mkdir($this->dest_dir);
    }
    return true;
  }

  /**
   * Returns upload directory
   * @return string
   */
  public function get_destination() {
    return $this->dest_dir;
  }

  /**
   * Sets upload allowed extensions
   * @param array $exts
   */
  public function set_allowed_exts(...$exts) {
    $this->allowed_exts = [];
    foreach ($exts as $ext) {
      array_push($this->allowed_exts, $ext);
    }
  }

  /**
   * Returns upload allowed extensions
   * @return array
   */
  public function get_allowed_exts() {
    return $this->allowed_exts;
  }
  
  /**
   * Sets upload max size as shorthand value (eg. 100M/3G/etc..)!
   * Cannot exceed the size set in the PHP.ini file!
   * @param integer $size
   * @return boolean
   */
  public function set_max_size($size) {
    $size = self::shorthand_to_value($size);
    if (self::file_upload_max_size() > $size) {
      $this->max_size = $size;
      return true;
    }
    return false;
  }

  /**
   * Returns upload max size
   * @return integer
   */
  public function get_max_size() {
    return $this->max_size;
  }

  /**
   * Returns last upload message
   * @return string FILE_NULL, DEST_NOT_DIR, DEST_NOT_WRITABLE, SIZE_LIMIT_EXCEEDED, TYPE_NOT_ALLOWED, UPLOAD_FAILED, UPLOAD_DONE
   */
  public function get_last_upload_msg() {
    return $this->last_upload_msg;
  }

  /**
   * Returns last uploaded file name
   * @return string
   */
  public function get_last_upload_filename() {
    return $this->last_upload_filename;
  }

  /**
   * Deletes last uploaded file
   * @return boolean
   */
  public function del_last_upload_file() {
    if (is_file($this->last_upload_filepath)) {
      return unlink($this->last_upload_filepath);
    }
    return false;
  }

  /**
   * Uploads the file
   * @param string $file
   * @param string $new_filename?
   * @return boolean
   */
  public function upload_file($file, $new_filename = null) {
    if (empty($file)) {
      error_log("Uploader class ::: Destination directory not set!");
      $this->last_upload_msg = 'FILE_NULL';
      return false;
    }

    if (empty($this->dest_dir)) {
      error_log("Uploader class ::: Destination directory not set!");
      $this->last_upload_msg = 'DEST_NOT_SET';
      return false;
    }

    if (!is_dir($this->dest_dir)) {
      error_log("Uploader class ::: Given path ({$this->dest_dir}) is not a directory!");
      $this->last_upload_msg = 'DEST_NOT_DIR';
      return false;
    }

    if (!is_writable($this->dest_dir)) {
      error_log("Uploader class ::: Given path ({$this->dest_dir}) is not writable!");
      $this->last_upload_msg = 'DEST_NOT_WRITABLE';
      return false;
    }

    $filename = $file['name'];
    $file_ext = self::get_file_extension($file);
    $filesize = $file['size'];

    if ($this->max_size > 0 && $filesize > $this->max_size) {
      error_log("Uploader class ::: File size exceeded the size limit!");
      $this->last_upload_msg = 'SIZE_LIMIT_EXCEEDED';
      return false;
    }

    if (!empty($this->allowed_exts) && !in_array($file_ext, $this->allowed_exts)) {
      error_log("Uploader class ::: File type not allowed!");
      $this->last_upload_msg = 'TYPE_NOT_ALLOWED';
      return false;
    }

    if (empty($new_filename)) {
      $new_filename = md5($_SERVER['REMOTE_ADDR'] . time());
    }

    $this->last_upload_filename = "{$new_filename}.{$file_ext}";
    $file_path = $this->dest_dir . $this->last_upload_filename;

    if (!move_uploaded_file($file["tmp_name"], $file_path)) {
      error_log("Uploader class ::: Upload failed, try later!");
      $this->last_upload_msg = 'UPLOAD_FAILED';
      return false;
    }

    $this->last_upload_msg = 'UPLOAD_DONE';
    return true;
  }

  /**
   * Returns file extension
   * @param string $file
   * @return string
   */
  private static function get_file_extension($file) {
    $tmp = $file['type'];
    $ext = substr($tmp, stripos($tmp, '/') + 1);
    return strtolower($ext);
  }

  /**
   * Returns PHP.ini max upload size in bytes
   * @return integer
   */
  private static function file_upload_max_size() {
    static $max_size = -1;
    
    if ($max_size < 0) {
      $post_max_size = self::shorthand_to_value(ini_get('post_max_size'));
      $upload_max_size = self::shorthand_to_value(ini_get('upload_max_filesize'));
      $max_size = ($upload_max_size > 0 && $upload_max_size > $post_max_size) ? $post_max_size : $upload_max_size;
    }

    return $max_size;
  }

  /**
   * Cleans string from non-numbers and returns the integer value
   * @param string $string
   * @return integer
   */
  private static function shorthand_to_value($string) {
    $shorthand = preg_replace('/[^a-z]/i', '', $string); // Remove all digits (case-insensitive)
    $size = intval(preg_replace('/[^0-9\.]/', '', $string)); // Remove all letters
    return round($size * ($shorthand ? pow(1024, stripos('bkmg', $shorthand)) : 1)); // Calculate size in bytes
  }
}
