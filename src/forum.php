<?php
  // Load CORE scripts
  require_once('config.php');
  require_once(INCPATH . 'utils/database.php');
  require_once(INCPATH . 'utils/functions.php');

  // Start session manager
  session_start();

  // Go to not found page if $_GET['n'] isn't set
  if (!isset($_GET['n'])) {
    require_once(INCPATH . 'components/not-found.php');
    exit(1);
  }

  // Load forum data
  $FORUM_DATA = get_forum_data($_GET['n']);
  if (is_null($FORUM_DATA)) {
    require_once(INCPATH . 'components/not-found.php');
    exit(1);
  }

  $FORUM_ID = $FORUM_DATA['forum_id'];
  $PAGE_TITLE = $FORUM_DATA['forum_title'];
  $FORUM_URI = 'f/' . $FORUM_DATA['forum_name'];
  $FORUM_LOGO = $FORUM_DATA['forum_logo'];
  $FORUM_ABOUT = $FORUM_DATA['forum_about'];
  $FORUM_DATE = date('M d, Y', $FORUM_DATA['forum_date']);

  // Add page script
  add_footer_script('/assets/js/forum.js');

  // Render WebSite from here on
  require_once(INCPATH . 'components/header.php');
  require_once(INCPATH . 'components/forum-head.php');
?>

<div class="container flex-grow-1 px-lg-5">
  <div class="row">
    <!-- Posts Column -->
    <div id="posts" class="col">

      <div class="text-center my-3">
        <div class="spinner-grow text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

    </div>

    <!-- Second Column -->
    <div class="col-lg-4">

      <div class="card my-3">
        <div class="card-header">
          About Forum
        </div>
        <div class="card-body">
          <p class="card-text"><?= $FORUM_ABOUT ?></p>
          <span class="forum-about-date px-1"><i class="fas fa-birthday-cake"></i> Created <span
              style="font-weight:500;"><?= $FORUM_DATE ?></span></span>
          <button id="new-post-btn" class="btn btn-secondary btn-block">Create Post</button>
        </div>
      </div>

      <?php @include_once(INCPATH . 'components/site-cards.php') ?>

    </div>
  </div>
</div>
<script>window.FORUM_ID = <?= $FORUM_ID ?></script>
<?php require_once(INCPATH . 'components/footer.php') ?>