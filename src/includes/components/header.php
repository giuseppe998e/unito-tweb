<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/lib/ibm-plex-sans.min.css">
  <link rel="stylesheet" href="/assets/css/lib/fa-all.min.css">
  <link rel="stylesheet" href="/assets/css/lib/tictactoe.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/post.css">

  <title><?= print_page_title() ?></title>
</head>

<body class="min-vh-100">
  <?php require_once(INCPATH . 'components/login-modal.php');
        require_once(INCPATH . 'components/newpost-modal.php'); ?>
  <div id="root" class="flex-column min-vh-100 d-flex">
    <nav class="navbar navbar-light sticky-top">
      <a class="navbar-brand" href="/">
        <img class="d-inline-block align-top" src="/assets/img/logo.png" width="30" height="30" alt="Logo"
          loading="lazy">
        <span>twebbit</span>
      </a>
      <div class="form-inline">
        <div class="btn-group">
        <a id="navbar-dropdown" class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="<?= get_user_photo() ?>" alt="Profile photo" />
          <?= get_user_name() ?>
        </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-dropdown">
            <a class="dropdown-item" href="/user.php?id=<?= get_user_id() ?>"><i class="fas fa-address-card"></i> My profile</a>
            <a id="logout" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-sign-out-alt"></i> Log out</a>
          </div>
        </div>
      </div>
    </nav>
    <!-- START PAGE CONTENT -->