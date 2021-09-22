// Post page
$(document).ready(() => {
  // --------------------------------
  // Handle post new comment
  // --------------------------------
  var newCommentForm = $('#new-comment')
  if (newCommentForm.length) {
    var newCommentBtn = $('#new-comment button'),
      newCommentText = $('#new-comment textarea')


    newCommentForm.on('submit', e => {
      e.preventDefault()
      newCommentBtn.attr('disabled', true)

      var formData = new FormData()
      formData.append('post_id', window.POST_ID)
      formData.append('text', newCommentText.val())

      $.ajax({
          url: '/rest.php?comments',
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
          newCommentBtn.attr('disabled', false)

          // Send error to user
          var errStr = 'Post new comment failed: ' + (() => {
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

  // --------------------------------
  // Loads post comments
  // --------------------------------
  var postComments = $('#comments')
  if (postComments.length) {
    var commentRender = (userID, userName, commText, commDate) => {
      return `
      <div class="comment py-2">
        <div class="comment-head"><a href="/user.php?id=${userID}">u/${userName}</a> • ${relativeDate(commDate)} ago</div>
        <div class="comment-body">${commText}</div>
      </div>
      `
    }

    var offset = 0,
      limit = 30,
      loadComments = () => $.getJSON(`/rest.php?comments=${window.POST_ID}&offset=${offset}&limit=${limit}`, json => {
        if (!json.ok) {
          showAlert({
            text: 'An error occurred while loading the post comments',
            type: 'danger'
          })
          return
        }

        if (offset == 0) {
          postComments.empty()
        }

        if (json.data.length == 0) {
          showAlert({
            text: 'No more comments...',
            type: 'warning',
            timeout: 0.75
          })
          return;
        }

        offset += limit;

        json.data.forEach(c => postComments.append(
          commentRender(
            c.user_id,
            c.user_name,
            c.comment_text,
            c.comment_date
          )
        ))
      })

    // Load first comments
    loadComments()

    // Handle page scroll in order to load next comments
    $(window).scroll(() => {
      var position = $(window).scrollTop(),
        bottom = $(document).height() - $(window).height()

      // If end of page -> Load posts
      if (position == bottom) {
        loadComments()
      }
    })
  }
})