<?php
  // Load CORE scripts
  require_once('config.php');
  require_once(INCPATH . 'utils/database.php');
  require_once(INCPATH . 'utils/functions.php');

  // Start session manager
  session_start();

  // Go to not found page if $_GET['id'] isn't set
  if (!isset($_GET['id'])) {
    require_once(INCPATH . 'components/not-found.php');
    exit(1);
  }

  // Load post data
  $POST_DATA = get_post_data($_GET['id']);
  if (is_null($POST_DATA)) {
    require_once(INCPATH . 'components/not-found.php');
    exit(1);
  }

  $FORUM_ID = $POST_DATA['forum_id'];
  $PAGE_TITLE = $POST_DATA['forum_title'];
  $FORUM_URI = 'f/' . $POST_DATA['forum_name'];
  $FORUM_LOGO = $POST_DATA['forum_logo'];
  $FORUM_ABOUT = $POST_DATA['forum_about'];
  $FORUM_DATE = date('M d, Y', $POST_DATA['forum_date']);

  $POST_TITLE = $POST_DATA['post_title'];
  $POST_CONTENT = $POST_DATA['post_content'];
  $POST_AUTHOR = $POST_DATA['user_name'];
  $POST_AUTHOR_ID = $POST_DATA['user_id'];
  $POST_LIKES = $POST_DATA['post_likes'] - $POST_DATA['post_dislikes'];
  $POST_COMMENTS = $POST_DATA['post_comments'];
  $POST_DATE = get_relative_date($POST_DATA['post_date']);

  // Add page script
  add_footer_script('/assets/js/post.js');

  // Render WebSite from here on
  require_once(INCPATH . 'components/header.php');
  require_once(INCPATH . 'components/forum-head.php');
?>

<div class="container flex-grow-1 px-lg-5">
  <div class="row">
    <div class="col">
      <!-- Post -->
      <div class="post my-3">
        <div class="post-block">
          <!-- Post Text -->
          <div class="post-left">
            <a class="arrow-up" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a>
            <span class="post-likes" data-value="<?= $POST_LIKES ?>"><?= short_number($POST_LIKES) ?></span>
            <a class="arrow-down" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a>
          </div>
          <div class="post-right pb-2">
            <div class="post-head">
              Posted by <a class="post-user" href="/user.php?id=<?= $POST_AUTHOR_ID ?>">u/<?= $POST_AUTHOR ?></a>
              <?= $POST_DATE ?> ago
            </div>
            <div class="post-body">
              <h3 id="post-title"><?= $POST_TITLE ?></h3>
              <span id="post-text">
                <?= $POST_CONTENT ?>
              </span>
            </div>
            <div class="post-footer">
              <a href="#comment"><i class="fas fa-comment-alt"></i> <?= $POST_COMMENTS ?> Comments</a>
            </div>
            <!-- New Comment -->
            <div class="mt-3">
              <form id="new-comment">
                <div class="form-group">
                  <span style="font-size: small;">Comment as <a
                      href="/user.php?id=<?= get_user_id() ?>">u/<?= get_user_name() ?></a></span>
                  <textarea class="form-control" rows=3 placeholder="What are your throughts?" maxlength="512" required></textarea>
                </div>
                <button class="btn btn-primary float-right" type="submit">Comment</button>
              </form>
            </div>
            <!-- Users Comment -->
            <div id="comments" class="mt-3">
              <div class="text-center">
                <div class="spinner-grow text-primary" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">

      <div class="card my-3">
        <div class="card-header">
          About Forum
        </div>
        <div class="card-body">
          <p class="card-text"><?= $FORUM_ABOUT ?></p>
          <span class="forum-about-date px-1"><i class="fas fa-birthday-cake"></i> Created <span
              style="font-weight:500;"><?= $FORUM_DATE ?></span></span>
        </div>
      </div>

      <?php @include_once(INCPATH . 'components/site-cards.php') ?>
    </div>
  </div>
</div>
<script>window.POST_ID = <?= $_GET['id'] ?></script>

<?php require_once(INCPATH . 'components/footer.php') ?>