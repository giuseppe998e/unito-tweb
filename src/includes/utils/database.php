<?php

class Database {
  /**
   * Instance of DB connection
   * @var PDO|null
   */
  private $db_instance = null;

  /**
   * MySQL constructor
   * @param string $host
   * @param int    $port
   * @param string $database
   * @param string $user
   * @param string $password
   * @param string $charset?
   */
  public function __construct($host, $port, $database, $user, $password, $charset = 'utf8') {
    try {
      $this->db_instance = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=$charset", $user, $password, [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    } catch(PDOException $e) {
      error_log($e->getMessage());
    }
  }

  /**
   * Closes connection to DB
   */
  public function __destruct() {
    $this->db_instance = null;
  }


  // --------------------------------------------------
  //  Forum functions
  // --------------------------------------------------

  /**
   * Returns a list of forums
   * @param integer $offset?
   * @param integer $limit?
   * @return array|null
   */
  public function get_forums_list($offset = 0, $limit = 15) {
    return $this->read("SELECT forum_id, forum_name, forum_title, forum_logo FROM forums ORDER BY forum_id DESC LIMIT ? OFFSET ?", $limit, $offset);
  }

  /**
   * Returns a specific forum by id
   * @param integer $forum_id
   * @return array|null
   */
  public function get_forum($forum_id) {
    $result = $this->read('SELECT * from forums WHERE forum_id = ?', $forum_id);
    return count($result) > 0 ? $result[0] : null;
  }

  /**
   * Returns a specific forum by name
   * @param integer $forum_name
   * @return array|null
   */
  public function get_forum_by_name($forum_name) {
    $result = $this->read('SELECT * from forums WHERE forum_name = ?', $forum_name);
    return count($result) > 0 ? $result[0] : null;
  }


  // --------------------------------------------------
  //  User functions
  // --------------------------------------------------

  /**
   * Returns a list of users
   * @param integer $offset?
   * @param integer $limit?
   * @return array|null
   */
  public function get_users_list($offset = 0, $limit = 15) {
    return $this->read("SELECT user_id, user_name, user_photo FROM users ORDER BY user_id DESC LIMIT ? OFFSET ?", $limit, $offset);
  }

  /**
   * Returns a specific user by id
   * @param integer $user_id
   * @return array|null
   */
  public function get_user($user_id) {
    $result = $this->read('SELECT * FROM users WHERE user_id = ?', $user_id);
    return count($result) > 0 ? $result[0] : null;
  }

  /**
   * Returns a specific user by name
   * @param string $user_name
   * @return array|null
   */
  public function get_user_by_name($user_name) {
    $result = $this->read('SELECT * FROM users WHERE user_name = ?', $user_name);
    return count($result) > 0 ? $result[0] : null;
  }

  /**
   * Create a new user
   * @param string $user_name
   * @param string $user_password
   * @param string $user_photo
   * @return integer
   */
  public function create_user($user_name, $user_password, $user_photo) {
    $result = $this->write('INSERT INTO users (user_name, user_password, user_photo, user_date) VALUES (?, ?, ?, ?)', 
                            $user_name, $user_password, $user_photo, time());
    return $result ? $this->db_instance->lastInsertId() : -1;
  }


  // --------------------------------------------------
  //  Post functions
  // --------------------------------------------------

  /**
   * Returns a list of latest website posts
   * @param integer $offset?
   * @param integer $limit?
   * @return array|null
   */
  public function get_latest_posts($offset = 0, $limit = 15) {
    $result = $this->read("SELECT posts.*, COUNT(l1.is_like) AS post_likes, COUNT(l2.is_like) AS post_dislikes, forums.forum_name, users.user_name from posts 
                            JOIN users ON users.user_id = posts.user_id 
                            JOIN forums ON forums.forum_id = posts.forum_id 
                            LEFT JOIN likes l1 ON l1.post_id = posts.post_id AND l1.is_like = 1
                            LEFT JOIN likes l2 ON l2.post_id = posts.post_id AND l2.is_like = -1
                            GROUP BY posts.post_id
                            ORDER BY posts.post_date DESC 
                            LIMIT ? OFFSET ?", $limit, $offset);
    return $result;
  }

  /**
   * Returns a list of the latest forum posts
   * @param integer $forum_name
   * @param integer $offset?
   * @param integer $limit?
   * @return array|null
   */
  public function get_forum_posts($forum_name, $offset = 0, $limit = 15) {
    $result = $this->read("SELECT posts.*, COUNT(l1.is_like) AS post_likes, COUNT(l2.is_like) AS post_dislikes, users.user_name from posts 
                            JOIN users ON users.user_id = posts.user_id 
                            JOIN forums ON forums.forum_id = posts.forum_id 
                            LEFT JOIN likes l1 ON l1.post_id = posts.post_id AND l1.is_like = 1
                            LEFT JOIN likes l2 ON l2.post_id = posts.post_id AND l2.is_like = -1
                            WHERE forums.forum_name = ? 
                            GROUP BY posts.post_id
                            ORDER BY posts.post_date DESC 
                            LIMIT ? OFFSET ?", $forum_name, $limit, $offset);
    return $result;
  }

  /**
   * Returns a list of the latest user posts
   * @param integer $user_id
   * @param integer $offset?
   * @param integer $limit?
   * @return array|null
   */
  public function get_user_posts($user_id, $offset = 0, $limit = 15) {
    $result = $this->read("SELECT posts.*, COUNT(l1.is_like) AS post_likes, COUNT(l2.is_like) AS post_dislikes, forums.* from posts 
                            JOIN users ON users.user_id = posts.user_id 
                            JOIN forums ON forums.forum_id = posts.forum_id
                            LEFT JOIN likes l1 ON l1.post_id = posts.post_id AND l1.is_like = 1
                            LEFT JOIN likes l2 ON l2.post_id = posts.post_id AND l2.is_like = -1
                            WHERE posts.user_id = ? 
                            GROUP BY posts.post_id
                            ORDER BY posts.post_date DESC 
                            LIMIT ? OFFSET ?", $user_id, $limit, $offset);
    return $result;
  }

  /**
   * Returns a specific post
   * @param integer $post_id
   * @return array|null
   */
  public function get_post($post_id) {
    $result = $this->read("SELECT posts.*, COUNT(l1.is_like) AS post_likes, COUNT(l2.is_like) AS post_dislikes, forums.*, users.user_name from posts 
                            JOIN users ON users.user_id = posts.user_id 
                            JOIN forums ON forums.forum_id = posts.forum_id 
                            LEFT JOIN likes l1 ON l1.post_id = posts.post_id AND l1.is_like = 1
                            LEFT JOIN likes l2 ON l2.post_id = posts.post_id AND l2.is_like = -1
                            WHERE posts.post_id = ?", $post_id);
    return count($result) > 0 ? $result[0] : null;
  }

  /**
   * Update the likes of the post provided
   * @param integer $post_id
   * @param integer $user_id
   */
  public function like_post($post_id, $user_id) {
    $result = $this->write('INSERT INTO likes (post_id, user_id, is_like) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE is_like = 1', $post_id, $user_id);
    return $result;
  }

  /**
   * Update the dislikes of the post provided
   * @param integer $post_id
   * @param integer $user_id
   */
  public function dislike_post($post_id, $user_id) {
    $result = $this->write('INSERT INTO likes (post_id, user_id, is_like) VALUES (?, ?, -1) ON DUPLICATE KEY UPDATE is_like = -1', $post_id, $user_id);
    return $result;
  }

  /**
   * Create a new post
   * @param integer $forum_id
   * @param integer $user_id
   * @param string $title
   * @param string $content?
   */
  public function create_post($forum_id, $user_id, $title, $content = null) {
    $result = $this->write('INSERT INTO posts (forum_id, user_id, post_title, post_content, post_date) VALUES (?, ?, ?, ?, ?)', $forum_id, $user_id, $title, $content, time());
    return $result;
  }


  // --------------------------------------------------
  //  Comments functions
  // --------------------------------------------------

  /**
   * Returns the comments of a post
   * @param integer $post_id
   * @param integer $offset?
   * @param integer $limit?
   * @return array
   */
  public function get_post_comments($post_id, $offset = 0, $limit = 15) {
    $result = $this->read("SELECT comments.*, users.user_name FROM comments JOIN users ON users.user_id = comments.user_id WHERE post_id = ? ORDER BY comment_date DESC LIMIT ? OFFSET ?", $post_id, $limit, $offset);
    return $result;
  }

  /**
   * Adds a new comment to a post
   * @param integer $post_id
   * @param integer $user_id
   * @param string $text
   * @return boolean
   */
  public function add_post_comment($post_id, $user_id, $text) {
    try {
      $result = true;
      $this->db_instance->beginTransaction();

      $sth = $this->db_instance->prepare('INSERT INTO comments (post_id, user_id, comment_text, comment_date) VALUES (?, ?, ?, ?)');
      $result = $result && $sth->execute([$post_id, $user_id, $text, time()]);

      $sth = $this->db_instance->prepare('UPDATE posts SET post_comments = post_comments + 1 WHERE post_id = ?');
      $result = $result && $sth->execute([$post_id]);

      $this->db_instance->commit();
      return $result;
    } catch(PDOException $e) {
      error_log('DB error: ' . $e->getMessage());
      $this->db_instance->rollBack();
      return false;
    } finally {
      $sth = null;
    }
  }


  // --------------------------------------------------
  //  Util functions
  // --------------------------------------------------

  /**
   * Utility function that reads data from DB
   * @param string $query
   * @param array  $params
   * @return array|null
   */
  private function read($query, ...$params) {
    try {
      if (!is_null($this->db_instance)) {
        $sth = $this->db_instance->prepare($query);
        if ($sth->execute($params)) {
          return $sth->fetchAll();
        }
      }
    } catch(PDOException $e) {
      error_log('DB error: ' . $e->getMessage());
    } finally {
      $sth = null;
    }

    return null;
  }

  /**
   * Utility function that writes data to DB
   * @param string $query
   * @param array  $params
   * @return boolean
   */
  private function write($query, ...$params) {
    try {
      if (!is_null($this->db_instance)) {
        $sth = $this->db_instance->prepare($query);
        return $sth->execute($params);
      }
    } catch(PDOException $e) {
      error_log('DB error: ' . $e->getMessage());
    } finally {
      $sth = null;
    }

    return false;
  }
}
