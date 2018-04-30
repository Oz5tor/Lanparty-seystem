<?php
    // sessions
    if (true) {
      echo '<pre>'.PHP_EOL;
      echo 'SESSION: ';
      echo print_r($_SESSION).PHP_EOL;
      echo 'Global Setings: ';
      echo print_r($_GLOBAL).PHP_EOL;
      echo 'POST: ';
      echo print_r($_POST).PHP_EOL;
      echo 'GET: ';
      echo print_r($_GET).PHP_EOL;
      echo 'FILES: ';
      echo print_r($_FILES).PHP_EOL;
      echo '</pre>';
      //echo ini_get('upload_max_filesize');
    }
?>
