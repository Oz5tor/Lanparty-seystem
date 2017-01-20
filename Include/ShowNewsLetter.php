<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $URLID = $db_conn->real_escape_string($_GET['id']);
  $LetterResult = $db_conn->query("SELECT * FROM NewsLetter WHERE LetterID = '$URLID'");
  $rowcount = $LetterResult->num_rows;
  if($rowcount < 1){
    // fuck off
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
