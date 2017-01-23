<?php
    // sessions
    if (true) {
      echo '<pre>';
      echo 'SESSION';
      echo print_r($_SESSION);
      echo 'POST';
      echo print_r($_POST);
      echo 'GET';
      echo print_r($_GET);
      echo 'FILES';
      echo print_r($_FILES);
      echo '</pre>';
      //echo ini_get('upload_max_filesize');
    }
?>
