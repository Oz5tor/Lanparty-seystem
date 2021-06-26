<?php
    //unset($_SESSION['SQL']);
    // sessions
    if (true) {
      echo '<pre>'.PHP_EOL;
      echo 'SESSION: ';
      echo print_r($_SESSION).PHP_EOL;
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


<head>
<script src="https://checkout.reepay.com/checkout.js"></script>

<script type="text/javascript">
    var rp = new Reepay.ModalCheckout('hlpartyTestin123');
</script>
</head>
<body>
davs

<script type="text/javascript">
/*rp.addEventHandler(Reepay.Event.Accept, function(data) {
    console.log('Success', data);
});*/
</script>



<form name="signupform" method="post" action="">
<input type="text" name="name"/>
<script src="https://token.reepay.com/token.js"
    class="reepay-button"
    data-pubkey="pub_43db84e1e0283112fe314c9b1063213b"
    data-text="Betal"
    data-language ="da"
    data-amount = "250"
    data-currency = "DKK"
    orderId = "bob1"
    data-recurring="false">
</script>
</form>

</body>
