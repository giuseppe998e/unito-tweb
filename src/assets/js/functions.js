// Show site alert method
var showAlert = ({ text, type = 'info', timeout = 1.5 }) => {
  var alert = document.createElement('div')
  alert.classList.add('alert', `alert-${type}`)
  alert.setAttribute('role', 'alert')
  alert.textContent = text
  alert.innerHTML += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'

  if (timeout > 0) {
    setTimeout(() => alert.remove(), timeout * 1000)
  }

  $('#alerts-zone').append(alert)
}

// Unix date to relative date method
var relativeDate = utime => {
  var seconds = Math.floor(Date.now() / 1000) - utime,
    interval = Math.floor(seconds / 31536000)
  if (interval > 1) return interval + ' year(s)'

  interval = Math.floor(seconds / 2592000)
  if (interval > 1) return interval + ' month(s)'

  interval = Math.floor(seconds / 86400)
  if (interval >= 1) return interval + ' day(s)'

  interval = Math.floor(seconds / 3600)
  if (interval >= 1) return interval + ' hour(s)'

  interval = Math.floor(seconds / 60)
  if (interval > 1) return interval + ' minute(s)'

  return Math.floor(seconds) + ' second(s)'
}

// Integer to short number method
var shortNumber = num => {
  for (var i = 0; num >= 1000; i++) num /= 1000
  return num + '\0kMBT'.charAt(i)
}

// Html post render method
var postRender = (forumName, userID, userName, postID, postDate, postTitle, postContent, postLikes, postComments) => {
  var forumLink = ('string' === typeof forumName) ? `<a href="forum.php?n=${forumName}">f/${forumName}</a>&nbsp;•&nbsp;` : ''
  return `
  <div class="post my-3" data-id="${postID}">
    <div class="post-block">
      <div class="post-left">
        <a class="arrow-up" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a>
        <span class="post-likes" data-value="${postLikes}">${shortNumber(postLikes)}</span>
        <a class="arrow-down" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a>
      </div>
      <div class="post-right">
        <div class="post-head">
          ${forumLink}Posted by <a class="post-user" href="/user.php?id=${userID}">u/${userName}</a> ${relativeDate(postDate)} ago
        </div>
        <div class="post-body">
          <h3>${postTitle}</h3>
          <span>${postContent}</span>
          <a class="stretched-link" href="/post.php?id=${postID}"></a>
        </div>
        <div class="post-footer">
          <a href="/post.php?id=${postID}#comment"><i class="fas fa-comment-alt"></i> ${shortNumber(postComments)} Comments</a>
        </div>
      </div>
    </div>
  </div>`
}