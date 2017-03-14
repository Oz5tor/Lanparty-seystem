<?php
  $RegErroMSG = array();
  $FormAOKAY = 0;
  if(!isset($_GET['thread']) && !isset($_GET['category']) && isset($_SESSION['Admin']) && $_SESSION['Admin'] == 1) {
    if($_POST['CategoryName'] == '') {$RegErroMSG[] .='Kategori navn'; $FormAOKAY = 1;}
    if($_POST['CategoryDesc'] == '') {$RegErroMSG[] .='Kategori beskrivelse'; $FormAOKAY = 1;}

    if($FormAOKAY == 0){
      // For successfully filled
      // injection prevention
      $CategoryName = $db_conn->real_escape_string($_POST['CategoryName']);
      $CategoryDesc = $db_conn->real_escape_string($_POST['CategoryDesc']);
      $CreateTime = time();

      if($db_conn->query("INSERT INTO `ForumCategory` (Name, Description, CreationDate) VALUES ('$CategoryName', '$CategoryDesc', '$CreateTime')")){}
    } // if formOKAY end
  } elseif (!isset($_GET['thread']) && isset($_GET['category']) && isset($_SESSION['UserID']) && $_SESSION['UserID'] == 1) {
    if($_POST['ThreadName'] == '') {$RegErroMSG[] .='Trådnavn'; $FormAOKAY = 1;}
    if($_POST['ReplyMessage'] == '') {$RegErroMSG[] .='Besked'; $FormAOKAY = 1;}

    if($FormAOKAY == 0){
      // For successfully filled
      // injection prevention
      $ThreadName = $db_conn->real_escape_string($_POST['ThreadName']);
      $ReplyMessage = $db_conn->real_escape_string($_POST['ReplyMessage']);
      $CategoryID = $db_conn->real_escape_string($_GET['category']);
      $Author = $db_conn->real_escape_string($_SESSION['UserID']);
      $CreateTime = time();

      $sql = "INSERT INTO `ForumThread` (Name, CategoryID, Author, CreationDate) VALUES ('$ThreadName', '$CategoryID', '$Author', '$CreateTime')";
      //if($db_conn->query($sql)){}
      if ($db_conn->query($sql) === TRUE) {

        $ThreadID = $db_conn->insert_id;
        if($db_conn->query("INSERT INTO `ForumReplies` (Content, ThreadID, Author, CreationDate) VALUES ('$ReplyMessage', '$ThreadID', '$Author', '$CreateTime')")){}
      }
    } // if formOKAY end
  } elseif (isset($_GET['thread']) && isset($_GET['category']) && isset($_SESSION['UserID']) && $_SESSION['UserID'] == 1) {
    if($_POST['Reply'] == '') {$RegErroMSG[] .='Svar'; $FormAOKAY = 1;}

    if($FormAOKAY == 0){
      // For successfully filled
      // injection prevention
      $Reply = $db_conn->real_escape_string($_POST['Reply']);
      $ThreadID = $db_conn->real_escape_string($_GET['thread']);
      $CategoryID = $db_conn->real_escape_string($_GET['category']);
      $Author = $db_conn->real_escape_string($_SESSION['UserID']);
      $CreateTime = time();

      $sql = "INSERT INTO `ForumReplies` (Content, ThreadID, Author, CreationDate) VALUES ('$Reply', '$ThreadID', '$Author', '$CreateTime')";
      $LatestThread = $db_conn->query("SELECT * FROM `ForumReplies` WHERE ThreadID = " . $ThreadID . " ORDER BY CreationDate DESC LIMIT 1");
      $ActualLatestThread = mysqli_fetch_assoc($LatestThread);
      if($ActualLatestThread['Author'] != $_SESSION['UserID']) {
        if ($db_conn->query($sql) === TRUE) {
        }
      } else {
        $_SESSION['MsgForUser'] = "Du er den sidste der har lavet en kommentar. Editer i stedet beskeden.";
        header("Location: index.php?page=Forum&category=" . $CategoryID . "&thread=" . $ThreadID . "#hlpf_menu");
        exit;
      }
    }
  } // if formOKAY end
?>