<?php
require_once("class/MailGunSendMail.php");
$Sending = 0;
if(isset($_GET['id'])){$URLID = $db_conn->real_escape_string($_GET['id']);}
if(isset($_POST['Save'])){
  $Title = $db_conn->real_escape_string($_POST['Title']); # = Subject
  if(isset($_POST['Send'])){$Send = $db_conn->real_escape_string($_POST['Send']);}else {$Send = '0';}
  if(isset($_POST['Members'])){$membersOnly = $db_conn->real_escape_string($_POST['Members']);}else {$membersOnly = '0';}
  #$Body  = $db_conn->real_escape_string($_POST['AdminTinyMCE']); # = what to put in the body of the mail function
  $Body  = $_POST['AdminTinyMCE']; # = what to put in the body of the mail function
  $Aurthor = $_SESSION['UserID']; # is saved to see whom there have made this newsletter
  if ($Send == 1) {$Send = time();} ## If "udsend" Checkbox is checked then defined $Send as Unixtimestamp
  # ==== Edit or New/copy ====
  if ($action == "Edit") {
    # Update statement
    $NewsLetterStatement = $db_conn->prepare("UPDATE NewsLetter SET `Subject` = ?, `Body` = ?, `SentDate` = '$Send', `Author` = '$Aurthor', MembersOnly = '$membersOnly'  WHERE `LetterID` = ?");
    $NewsLetterStatement->bind_param("ssi", $Title, $Body, $URLID);    
  }else {
    $NewsLetterStatement = $db_conn->prepare("INSERT INTO `NewsLetter`(`Subject`, `Body`, `SentDate`, `Author`, `MembersOnly`) VALUES (?,?,'$Send','$Aurthor','$membersOnly')");
    $NewsLetterStatement->bind_param("ss", $Title, $Body);
  }
  # ==========================
  $Recivers = array();
  if ($membersOnly == 1) {
    # if Member is checked
    $y = date("Y");
    $ReciversSTMT = $db_conn->prepare("SELECT Users.Email From UserMembership Inner Join Users On UserMembership.UserID = Users.UserID Where UserMembership.Year = '$y'");
  }else{
    $ReciversSTMT = $db_conn->prepare("SELECT Users.Email From Users Where NewsLetter = 1");
  }
  $ReciversSTMT->bind_result($email);
  $ReciversSTMT->execute();
  while($ReciverEmail = $ReciversSTMT->fetch()) {
    $Recivers[] .= $email;
  }
  # ==========================
  if($NewsLetterStatement->execute()){
    # IF sucessfull inserted into Database continue with doing more stuff.
    if ($Send != 0) {
      # if "Udsend" is checked
      $Key        = $_GLOBAL['MailgunKey'];
      $HTML       = $Body;#Insert body from Newsletter
      #$To         = "torsoya@gmail.com";#define this when calling the functions instead of here for newsletters
      $From       = $_GLOBAL['SendMailFrom'];
      $Subject    = $Title;#Insert Subject from Newsletter
      $Sending = 1;
      foreach ($Recivers as $key => $value) {
        MailGunSender($From, $value, $Subject, $HTML, $Key);
        echo "$value er sat i kø.";
        echo "</br>";
        echo '<meta http-equiv="refresh" content="10; url=?page=Admin&subpage=NewsLetter#admin_menu" />';
      }
    }
  }else {
    echo "NewsLetter did not get inserted into the DB and nothing else was done";
  }
}// if submmit send

#=====================================================================
## Interface starts here for creating/deiting of Newsletter
#=====================================================================
// edit or template
if(isset($_GET['action']) && ( ($_GET['action'] == 'Edit') || ($_GET['action'] == 'Template') ) ){
  $URLID = $db_conn->real_escape_string($_GET['id']);
  $action = $db_conn->real_escape_string($_GET['action']);
  $result = $db_conn->query("SELECT Subject, Body, SentDate FROM NewsLetter WHERE LetterID = '$URLID'");
  $row = $result->fetch_assoc();
  if( ($action == 'Edit') && ($row['SentDate'] > 0) ){
    $LetterExist = 0;
    //echo '<b><p class="text-center"> Det valgte nyheds brev kan ikke redigers, da det er blevet udsendt</p></b>';
   # header("Location: index.php?page=Admin&subpage=NewsLetter#admin_menu"); // back to newsletter list
  }else{
    $LetterExist = 1;
    $Subject  = $row['Subject'];
    $Body     = $row['Body'];
    $sent     = $row['SentDate'];
  }
}
if ($Sending == 0) {

  if( (isset($LetterExist) && $LetterExist == 1) || $action == 'New')
  {
  ?>
  <form method="post" action="">
    <div class="form-group col-lg-6">
      <label class="control-label" for="Title">Emne: </label>
      <input class="form-control" type="text" maxlength="50" name="Title" id="Title" required size="50" value="<?php if($action == 'Edit'){echo $Subject;} ?>">
    </div>
    <div class="form-group form-inline col-lg-3">
      <label>Udsend:</label> 
      <input type="checkbox" value="1" name="Send">
    </div>
    <div class="form-group form-inline col-lg-3">
      <label>Medlemmer:</label> 
      <input type="checkbox" value="1" name="Members">
    </div>
    <div class="form-group col-lg-12">
      <textarea rows="25" id="AdminTinyMCE" name="AdminTinyMCE"><?php if(isset($LetterExist)){echo $Body;} ?></textarea>
    </div>
    <div class="form-group text-center col-lg-12">
      <input class="btn btn-default" type="submit" value="Gem" name="Save" />
    </div>
  </form>
  <?php
  }
  
}
?>

