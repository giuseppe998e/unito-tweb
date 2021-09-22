<?php
  // Load CORE scripts
  require_once('config.php');
  require_once(INCPATH . 'utils/database.php');
  require_once(INCPATH . 'utils/functions.php');

  // Start session manager
  session_start();

  // Check if already logged in
  if (get_user_id() > 0) {
    header('Location: http://' . $_SERVER['HTTP_HOST']);
    exit(0);
  }

  // Hide login modal
  $HIDE_LOGIN = true;

  // Add page script
  add_footer_script('/assets/js/register.js');

  // Render WebSite from here on
  require_once(INCPATH . 'components/header.php');
?>

<div class="container mt-5">
  <div class="row">
    <div class="col"></div>
    <div class="col-lg-6">
      <form class="register p-3">
        <div class="form-group">
          <label class="user-select-none" for="reg-username">Username</label>
          <input id="reg-username" class="form-control" type="text" required>
        </div>
        <div class="form-group">
          <label class="user-select-none" for="reg-passwd">Password</label>
          <input id="reg-passwd" class="form-control" type="password" required>
        </div>
        <div class="form-group">
          <label class="user-select-none">Profile photo</label>
          <div class="reg-photo">
            <div id="uploader">
              <input id="uploader-fe" style="display:none" accept="image/*" type="file"/>
              <p>Drop image here or <a id="uploader-link" href="javascript:void(0)">Choose an image</a></p>
            </div>
            <img id="photo-preview" src="/assets/img/nobody.svg" title="Preview" alt="Preview" />
          </div>
        </div>
        <div class="d-flex">
          <button id="reg-submit" class="btn btn-primary ml-auto" type="submit">Register</button>
        </div>
      </form>
    </div>
    <div class="col"></div>
  </div>
</div>

<?php require_once(INCPATH . 'components/footer.php') ?>
