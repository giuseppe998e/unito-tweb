<?php if (isset($FORUM_DATA)): ?>
<div id="new-post-modal" class="modal fade" tabindex="-1" aria-labelledby="new-post-modal-h3" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="new-post-modal-h3" class="user-select-none modal-title">Create new post</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <input id="new-post-modal-title" class="form-control" type="text" placeholder="Title" maxlength="256" required>
          </div>
          <div class="form-group">
            <textarea id="new-post-modal-content" class="form-control" rows=3 placeholder="Text (optional)"></textarea>
          </div>
          <div class="text-right">
            <button class="btn btn-primary" type="submit">Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>