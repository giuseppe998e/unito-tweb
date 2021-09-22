$(document).ready(() => {
  var loginModal = $('#login-modal'),
    loginForm = $('#login-modal form'),
    loginSubmitBtn = $('#login-modal-submit'),
    loginUsername = $('#login-modal-username'),
    loginPassword = $('#login-modal-passwd')
    
  // Show modal
  loginModal.modal('show')

  // Handle login form submit action
  loginForm.on('submit', e => {
    // Prevend submit default action and disable button
    e.preventDefault()
    loginSubmitBtn.attr('disabled', true)

    // Create post body
    var formData = new FormData()
    formData.append('user_name', loginUsername.val())
    formData.append('user_pwd', loginPassword.val())

    // Send Ajax POST request
    $.ajax({
        url: '/rest.php?account=login',
        method: 'post',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        cache: false
      })
      .done(() => {
        setTimeout(() => location.reload(), 2)

        showAlert({
          text: 'Welcome back user! Please wait...',
          type: 'success'
        })
      })
      .fail(r => {
        var json = r.responseJSON

        // Enable the login button again
        loginSubmitBtn.attr('disabled', false)

        // Send error to user
        var errStr = 'Login failed: ' + (() => {
          switch (json.error.message) {
            case 'ALREADY_LOGGED_IN':
              return 'You are already logged in'
            case 'USER_NOT_EXIST':
              return 'This user not exist!'
            case 'WRONG_PWD':
              return 'Wrong password provided!'
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
})