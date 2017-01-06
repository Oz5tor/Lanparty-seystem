<?php 
if(isset($_POST['Save'])){
  $Title = $db_conn->real_escape_string($_POST['Title']);
  if(isset($_POST['Send'])){$Send = $db_conn->real_escape_string($_POST['Send']);}else {$Send = '0';}
  $Body  = $db_conn->real_escape_string($_POST['AdminTinyMCE']);
  $Aurthor = $_SESSION['UserID'];
  if($action == 'Edit'){
    // update querry
    /*$db_conn->query("UPDATE NewsLetter SET  Subject = '$Title', Body = '$Body', SentDate = '$Send', Author = '$Aurthor'");
    header("Location: index.php?page=Admin&subpage=NewsLetters");*/
  }else{
    if($Send == 1){
      
      // call all users signed for news letters
      $NewsResult = $db_conn->query("Select Users.FullName, Users.Email, Users.NewsLetter From Users Where Users.NewsLetter = 1");
      while($NewsRow = $NewsResult->fetch_assoc()){
      
      // To send HTML mail, the Content-type header must be set
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=UTF-8';

      // Additional headers
      $headers[] = 'To: '.$NewsRow['FullName'].' <'.$NewsRow['FullName'].'>';
      $headers[] = 'From: HLParty Testin <noreply@hlpf.dk>';

      // Mail it
      mail($NewsRow['Email'], $Title, $Body, implode("\r\n", $headers));
      }// End of Users tehre want news
      // Insert querry
    if($Send != '0'){$Send = time($Send);}
    $db_conn->query("INSERT INTO NewsLetter (Subject, Body, SentDate, Author)
                                     VALUES ('$Title','$Body','$Send','$Aurthor')");
    }// if $Send = 1
    header("Location: index.php?page=Admin&subpage=NewsLetters");
  }// if action is not Edit
}// if submmit send
?>


<form method="post" action="">
  <table class="table hlpf_adminmenu">
    <tr>
      <td>
        <label for="Title">Emne: </label>
        <input type="text" maxlength="50" name="Title" id="Title" required size="50">
      </td>
      <td>
        <label>Udsend:</label> <input type="checkbox" value="1" name="Send">
      </td>
    </tr>
    <tr>
      <td colspan="2"><textarea rows="25" id="AdminTinyMCE" name="AdminTinyMCE"><?php if(isset($pageExist)){echo $row['Content'];} ?></textarea></td>
    </tr>
    <tr>
      <td colspan="3" class="text-center"><input class="btn btn-default" type="submit" value="Gem" name="Save" /></td>
    </tr>
  </table>
</form>
