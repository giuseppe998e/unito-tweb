// Register page
$(document).ready(() => {
  var regForm = $('form.register'),
    regSubmitBtn = $('#reg-submit'),
    regUploader = $('#uploader'),
    regUploaderLink = $('#uploader-link'),
    regUploaderFe = $('#uploader-fe:hidden'),
    regPhotoPrev = $('#photo-preview'),
    userPhoto = null

  // Checks, gets and makes a preview of user selected image
  var checkAndGetImage = files => {
    // Just one file
    if (files.length > 1) {
      showAlert({
        text: 'A maximum of one image can be uploaded!',
        type: 'danger'
      })
      return
    }

    // Regex check type
    if (!/image\/\S+/i.test(files[0].type)) {
      showAlert({
        text: 'The file must be an image!',
        type: 'danger'
      })
      return
    }

    userPhoto = files[0]

    var fr = new FileReader()
    fr.readAsDataURL(userPhoto)
    fr.onloadend = () => regPhotoPrev.attr('src', fr.result)
  }

  // Handle file-explorer uploader
  regUploaderLink.click(() => regUploaderFe.trigger('click'))
  regUploaderFe.on('change', () => checkAndGetImage(regUploaderFe[0].files))

  // Handle drop-in uploader
  regUploader.on('drag dragstart dragend dragover dragenter dragleave drop', e => {
      e.preventDefault()
      e.stopPropagation()
    })
    .on('dragover dragenter', () => {
      regUploader.addClass('is-dragover')
    })
    .on('dragleave dragend drop', () => {
      regUploader.removeClass('is-dragover')
    })
    .on('drop', e => checkAndGetImage(e.originalEvent.dataTransfer.files))

  // Handle register form submit action
  regForm.on('submit', e => {
    // Prevend submit default action and disable button
    e.preventDefault()
    regSubmitBtn.attr('disabled', true)

    // Create post body
    var formData = new FormData()
    formData.append('user_name', $('#reg-username').val())
    formData.append('user_pwd', $('#reg-passwd').val())
    formData.append('user_photo', userPhoto)

    // Send Ajax POST request
    $.ajax({
        url: '/rest.php?account=register',
        method: 'post',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        cache: false
      })
      .done(() => {
        setTimeout(() => document.location.href = '/', 2)

        showAlert({
          text: 'Welcome to Twebbit! Please wait ...',
          type: 'success'
        })
      })
      .fail(r => {
        var json = r.responseJSON

        // Enable the register button again
        regSubmitBtn.attr('disabled', false)

        // Send error to user
        var errStr = 'Register failed: ' + (() => {
          switch (json.error.message) {
            case 'USER_EXIST':
              return 'This use already exist!'
            case 'ALREADY_LOGGED_IN':
              return 'You are already logged in'
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