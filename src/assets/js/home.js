// Home page
$(document).ready(() => {
  // --------------------------------
  // Load last forums
  // --------------------------------
  var lastForums = $('#last-forums')
  if (lastForums.length) {
    $.getJSON('/rest.php?forums', json => {
      if (!json.ok) {
        showAlert({
          text: 'There was an error loading forums list',
          type: 'warning',
          timeout: 1
        })
        return
      }

      lastForums.empty()
      json.data.forEach(f => {
        lastForums.after(`<li class="list-group-item font-weight-bold"><a href="/forum.php?n=${f.forum_name}"><img style="height:1.5em;" src="${f.forum_logo}"/> ${f.forum_title}</a></li>`)
      })
    })
  }

  // --------------------------------
  // Load last users
  // --------------------------------
  var lastUsers = $('#last-users')
  if (lastUsers.length) {
    $.getJSON('/rest.php?users', json => {
      if (!json.ok) {
        showAlert({
          text: 'There was an error loading users list',
          type: 'warning',
          timeout: 1
        })
        return
      }

      lastUsers.empty()
      json.data.forEach(u => {
        lastUsers.after(`<li class="list-group-item font-weight-bold"><a href="/user.php?id=${u.user_id}"><img style="height:1.5em;" src="${u.user_photo}"/> ${u.user_name}</a></li>`)
      });
    })
  }
})