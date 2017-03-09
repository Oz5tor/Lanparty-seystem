<?php
  $RegErroMSG = array();
  $FormAOKAY = 0;
  if(is_null($_POST['subpage'])) {
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
  } elseif (!is_null($_GET['subpage']) && $_GET['page'] == 'Forum') {
    if($_POST['ThreadName'] == '') {$RegErroMSG[] .='Trådnavn'; $FormAOKAY = 1;}
    if($_POST['ReplyMessage'] == '') {$RegErroMSG[] .='Besked'; $FormAOKAY = 1;}

    if($FormAOKAY == 0){
      // For successfully filled
      // injection prevention
      $ThreadName = $db_conn->real_escape_string($_POST['ThreadName']);
      $ReplyMessage = $db_conn->real_escape_string($_POST['ReplyMessage']);
      $CategoryID = $db_conn->real_escape_string($_GET['subpage']);
      $ThreadID = $db_conn->real_escape_string($_POST['ThreadID']); // ???? Create a supersubpage and call it some garbage name?
      $Author = $db_conn->real_escape_string($_SESSION['UserID']); // ???? UserID?
      $CreateTime = time();

      if($db_conn->query("INSERT INTO `ForumThread` (Name, CategoryID, Author, CreationDate) VALUES ('$ThreadName', '$CategoryID', '$Author', '$CreateTime')")){}
      if($db_conn->query("INSERT INTO `ForumReplies` (Content, ThreadID, Author, CreationDate) VALUES ('$ReplyMessage', '$ThreadID', '$Author', '$CreateTime')")){}
    } // if formOKAY end
  }
?>