// Forum page
$(document).ready(() => {
  // --------------------------------
  // Handle forum new post modal
  // --------------------------------
  var newPostModal = $('#new-post-modal')
  if (newPostModal.length) {
    var newPostBtn = $('#new-post-btn'),
      newPostForm = $('#new-post-modal form'),
      newPostTitle = $('#new-post-modal-title'),
      newPostContent = $('#new-post-modal-content')

    newPostBtn.click(() => newPostModal.modal('show'))

    newPostForm.on('submit', e => {
      e.preventDefault()

      var formData = new FormData()
      formData.append('forum_id', window.FORUM_ID)
      formData.append('post_title', newPostTitle.val())
      formData.append('post_content', newPostContent.val())

      $.ajax({
          url: '/rest.php?posts',
          method: 'post',
          dataType: 'json',
          data: formData,
          processData: false,
          contentType: false,
          cache: false
        })
        .done(() => location.reload())
        .fail(r => {
          var json = r.responseJSON

          // Send error to user
          var errStr = 'Post failed: ' + (() => {
            switch (json.error.message) {
              case 'NOT_LOGGED_IN':
                return 'You are NOT logged in'
              default:
                return 'Unknown error!'
            }
          })()

          showAlert({
            text: errStr,
            type: 'danger'
          })

        })
    })
  }
})