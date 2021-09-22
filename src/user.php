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
    $USER_DATA = get_user_data($_GET['id']);
    if (is_null($USER_DATA)) {
      require_once(INCPATH . 'components/not-found.php');
      exit(1);
    }
  
    $PAGE_TITLE = $USER_DATA['user_name'];
    $USER_PHOTO = $USER_DATA['user_photo'];
    $USER_DATE = date('M d, Y', $USER_DATA['user_date']);



    // Add page script
    add_footer_script('/assets/js/user.js');
  
    // Render WebSite from here on
    require_once(INCPATH . 'components/header.php');
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

      <div id="user-about" class="card my-3">
        <div class="card-about-bg color-site-bg"></div>
        <div class="card-body">
          <img src="<?= $USER_PHOTO ?>" />
          <p class="card-text"><span class="font-weight-bold"><?= $PAGE_TITLE ?></span><p>
          <span class="forum-about-date px-1"><i class="fas fa-pizza-slice"></i> Registered on <span style="font-weight:500;"><?= $USER_DATE ?></span></span>
        </div>
      </div>

      <?php @include_once(INCPATH . 'components/site-cards.php') ?>

    </div>
  </div>
</div>

<?php require_once(INCPATH . 'components/footer.php') ?>