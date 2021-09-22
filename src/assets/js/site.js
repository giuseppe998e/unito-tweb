$(document).ready(() => {
  // --------------------------------
  // Handle logout link
  // --------------------------------
  $('#logout').click(() => $.ajax({
    url: '/rest.php?account',
    method: 'delete',
    success: () => location.reload()
  }))

  
  // --------------------------------
  // Load current page posts
  // --------------------------------
  var postsDiv = $('#posts')
  if (postsDiv.length) {
    // Save current unix timestamp
    var DATE_NOW = Math.floor(Date.now() / 1000)

    // Get cleaned location
    var page = (() => {
      var tmp = window.location.pathname
      return tmp.substr(1, tmp.indexOf('.') - 1)
    })()

    // RestAPI variables
    var restURI = '/rest.php?posts=' + (page != '' ? `${page}&${window.location.search.substr(1)}` : 'home'),
      offset = 0

    // Function that loads page posts
    var loadPosts = () => $.getJSON(restURI + `&offset=${offset}`, json => {
      if (!json.ok) {
        showAlert({
          text: 'An error occurred while loading posts',
          type: 'danger'
        })
        return
      }

      if (offset == 0) {
        postsDiv.empty()
      }

      if (json.data.length == 0) {
        showAlert({
          text: 'No more posts...',
          type: 'warning',
          timeout: 0.75
        })
        return;
      }

      offset += 15;

      json.data.forEach(post => postsDiv.append(
        postRender(
          post.forum_name,
          post.user_id,
          post.user_name,
          post.post_id,
          post.post_date,
          post.post_title,
          post.post_content,
          post.post_likes - post.post_dislikes,
          post.post_comments
        )
      ))
    })

    // Load posts for the first time
    loadPosts()

    // Handle page scroll in order to load next posts
    $(window).scroll(() => {
      var position = $(window).scrollTop(),
        bottom = $(document).height() - $(window).height()

      // If end of page -> Load posts
      if (position == bottom) {
        loadPosts()
      }
    })
  }


  // --------------------------------
  // Handle post like/dislike
  // --------------------------------
  var sendLikeOrDis = (postID, postLikes, action) => {
    $.ajax({
      url: `/rest.php?posts=${postID}&is_like=${action}`,
      method: 'put',
      cache: false
    })
    .done(() => {
      postLikesVal = postLikes.data('value')
      actionVal = (postLikesVal + action == 0) ? action * 2 : action;
      postLikes.data('value', postLikesVal + actionVal)
      postLikes.text(shortNumber(postLikesVal + actionVal))
    })
    .fail(() => showAlert({
      text: 'Unable to record last action...',
      type: 'danger'
    }))
  }

  // Handle post up-arrow button
  $(document).on('click', '.post a.arrow-up', e => {
    var postDiv = $(e.target).parents('.post'),
      postLikes = postDiv.find('.post-likes'),
      postID = window.POST_ID ? window.POST_ID : postDiv.attr('data-id')
    
    sendLikeOrDis(postID, postLikes, 1)
  })

  // Handle post down-arrow button
  $(document).on('click', '.post a.arrow-down', e => {
    var postDiv = $(e.target).parents('.post'),
      postLikes = postDiv.find('.post-likes'),
      postID = window.POST_ID ? window.POST_ID : postDiv.attr('data-id')

    sendLikeOrDis(postID, postLikes, -1)
  })


  // --------------------------------
  // Handler TicTacToe game
  // --------------------------------
  if ($('#ttt-card').length) {
    var ttt = TicTacToe('#ttt-card .ttt-board')
    var points = $('#ttt-card #ttt-points')
    ttt.setPointsHandler((h, c) => points.textContent = `${h} : ${c}`)
  }
})