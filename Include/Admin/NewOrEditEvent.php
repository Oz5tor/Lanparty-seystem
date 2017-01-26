<?php
if(isset($_GET['id'])){
  $tempID = $db_conn->real_escape_string($_GET['id']);

  if($result = $db_conn->query("Select * From Event Where EventID = '$tempID'")){
    $row = $result->fetch_assoc();
    $EventExist = true;
  }
}

if(isset($_POST['Save'])) {
  # PREF_SPLIT   0:YEAR, 1:MONTH, 2:DAY, 3:HOUR, 4:MINUTE.
  $StartDate = preg_split("/([-T:])/", $db_conn->real_escape_string($_POST['StartDate']));
  $EndDate   = preg_split("/([-T:])/", $db_conn->real_escape_string($_POST['EndDate']));
  $SeatsOpen = preg_split("/([-T:])/", $db_conn->real_escape_string($_POST['SeatsOpen']));
  $Title            = $db_conn->real_escape_string($_POST['Title']);
  # Uncomment for poster
  #$Poster           = $db_conn->real_escape_string($_POST['Poster']);
  # MKTIME(HOUR, MINUTE, SECOND, MONTH, DAY, YEAR)
  $Unix_StartDate   = mktime($StartDate[3], $StartDate[4], 0, $StartDate[1], $StartDate[2], $StartDate[0]);
  $Unix_EndDate     = mktime($EndDate[3], $EndDate[4], 0, $EndDate[1], $EndDate[2], $EndDate[0]);
  $Location         = $db_conn->real_escape_string($_POST['Location']);
  $Unix_SeatsOpen   = mktime($SeatsOpen[3], $SeatsOpen[4], 0, $SeatsOpen[1], $SeatsOpen[2], $SeatsOpen[0]);
  $Rules_ID         = $db_conn->real_escape_string($_POST['Rules']);
  if($action == 'Edit') {
    // edit Query
    if( $db_conn->query( "
        UPDATE Event
        SET Title = '$Title',
            StartDate = '$Unix_StartDate',
            EndDate = '$Unix_EndDate',
            Location = '$Location',
            Network = '$Network',
            SeatsOpen = '$Unix_SeatsOpen',
            Rules = '$Rules_ID'
        WHERE PageID = '$tempID'" ) ) {
      header("Location: index.php?page=Admin&subpage=Event#admin_menu");
    }
  } else {
    // Create Query
    if($db_conn->query("INSERT INTO Event (Title,StartDate,EndDate,Location,SeatsOpen,Rules)
                        VALUES ('$Title', '$Unix_StartDate', '$Unix_EndDate', '$Location', '$Unix_SeatsOpen', '$Rules_ID')")){
      header("Location: index.php?page=Admin&subpage=Event#admin_menu");
    }
  }
}
?>
<form method="post" action="">
  <div class="form-group col-lg-3">
    <label class="control-label" for="Title">Title</label>
    <input required class="form-control" type="text" name="Title" id="Title" value="<?php if(isset($EventExist)){echo $row['Title'];} ?>" maxlength="50"/>
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="Poster">Poster</label>
    <input class="form-control" type="file" id="Poster">
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="StartDate">Start Dato</label>
    <input class="form-control" required type="datetime-local" name="StartDate" id="StartDate" value="<?php if(isset($EventExist)){echo $row['StartDate'];} ?>" />
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="EndDate">Slut Dato</label>
    <input class="form-control" required type="datetime-local" name="EndDate" id="EndDate" value="<?php if(isset($EventExist)){echo $row['EndDate'];} ?>" />
  </div>
  <div class="form-group col-lg-6">
    <label class="control-label" for="Location">Lokation</label>
    <input class="form-control" required type="text" name="Location" id="Location" value="<?php if(isset($EventExist)){echo $row['Location'];} ?>" />
  </div>
  <div class="form-group col-lg-6">
    <label class="control-label" for="SeatsOpen">SeatsOpen</label>
    <input class="form-control" required type="datetime-local" name="SeatsOpen" id="SeatsOpen" value="<?php if(isset($EventExist)){echo $row['SeatsOpen'];} ?>" />
  </div>
  <div class="form-group col-lg-6">
    <label for="RuleSetSelect">Regelsæt (Vælg en)</label>
    <?php $GetRuleSets = $db_conn->query("
          SELECT
            Pages.PageID,
            Pages.PageTitle,
            Pages.LastEditedDate
          FROM
            Pages
          WHERE
            Pages.PageTitle like '%Regler%'
          ORDER BY
            Pages.LastEditedDate DESC
    "); ?>
    <select name="Rules" class="form-control" id="RuleSetSelect">
      <?php while( $RuleSets = $GetRuleSets->fetch_assoc() ) { ?>
        <option value="<?php echo $RuleSets['PageID'] ?>"><?php echo $RuleSets['PageTitle'] . " --- Sidst ændret den " . date("d M Y", $RuleSets['LastEditedDate']); ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="form-group col-lg-12">
    <input class="btn btn-primary" type="submit" value="Gem" name="Save" />
  </div>
</form>
