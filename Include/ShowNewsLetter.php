<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $URLID = $db_conn->real_escape_string($_GET['id']);
  $LetterResult = $db_conn->query("SELECT * FROM NewsLetter WHERE LetterID = '$URLID'");
  $rowcount = $LetterResult->num_rows;
  if($rowcount < 1){
    ?>
    <div class="hlpf_contentbox text-center center-block" style="width:700px">
    <h3>Det forespurgte nyhedsbrev kunne ikke findes beklager.</h3>
    <p><a class="btn btn-primary" href="index.php">Klik her for at komme til forsiden</a></p>
    </div>
    <?php
  }else{
    $LetterRow = $LetterResult->fetch_assoc();
    ?>
    <div class="hlpf_contentbox center-block" style="width:700px">
      <?php echo $LetterRow['Body']; ?>
    </div>
  <?php
  }
  ?>
<?php
}
?>
