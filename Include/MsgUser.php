<?php
if (isset($_SESSION['MsgForUser'])) { ?>
<div class="container">
  <div class="alert alert-dismissible alert-danger col-lg-6 col-md-8 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-2">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Fejl!</strong> <?= $_SESSION['MsgForUser'] ?>
  </div>
</div>
<?php
unset($_SESSION['MsgForUser']);
} ?>
