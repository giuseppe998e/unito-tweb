<?php
  if (!isset($HIDE_LOGIN) && get_user_id() < 1): // TODO < 1
    add_footer_script('/assets/js/login-modal.js');
?>
<div id="login-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
  aria-labelledby="login-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="login-modal-title" class="user-select-none modal-title">Log in to continue...</h3>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label class="user-select-none" for="login-modal-username">Username</label>
            <input id="login-modal-username" class="form-control" type="text" required>
          </div>
          <div class="form-group">
            <label class="user-select-none" for="login-modal-passwd">Password</label>
            <input id="login-modal-passwd" class="form-control" type="password" required>
          </div>
          <div class="d-flex">
            <span class="user-select-none" style="line-height:2.25rem;">Want to join? <a href="/register.php">Sign up</a></span>
            <button id="login-modal-submit" class="btn btn-primary ml-auto" type="submit">Log In</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
