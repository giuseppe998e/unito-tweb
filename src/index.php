<?php
  // Load CORE scripts
  require_once('config.php');
  require_once(INCPATH . 'utils/database.php');
  require_once(INCPATH . 'utils/functions.php');

  // Start session manager
  session_start();

  // Add page script
  add_footer_script('/assets/js/home.js');

  // Render WebSite from here on
  require_once(INCPATH . 'components/header.php');
?>

<div class="container px-lg-5">
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
        <div class="card-header">Last Forums</div>
        <ul id="last-forums" class="list-group list-group-flush">
          <li class="list-group-item text-center my-3">
            <div class="spinner-grow text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </li>
        </ul>
      </div>

      <div class="card my-3">
        <div class="card-header">Last Users</div>
        <ul id="last-users" class="list-group list-group-flush">
          <li class="list-group-item text-center my-3">
            <div class="spinner-grow text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </li>
        </ul>
      </div>

      <?php @include_once(INCPATH . 'components/site-cards.php') ?>

    </div>
  </div>
</div>

<?php require_once(INCPATH . 'components/footer.php') ?>