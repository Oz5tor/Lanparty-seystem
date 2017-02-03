<?php
if(isset($_GET['id'])){
  $tempID = $db_conn->real_escape_string($_GET['id']);

  if($result = $db_conn->query("Select * From Event Where EventID = '$tempID'")){
    $row = $result->fetch_assoc();
    $EventExist = true;
  }
}

if(isset($_POST['Save'])) {
  $StartDate = strtotime($db_conn->real_escape_string($_POST['StartDate']));
  $EndDate   = strtotime($db_conn->real_escape_string($_POST['EndDate']));
  $SeatsOpen = strtotime($db_conn->real_escape_string($_POST['SeatsOpen']));
  $Title     = $db_conn->real_escape_string($_POST['Title']);
  # Uncomment for poster
  #$Poster           = $db_conn->real_escape_string($_POST['Poster']);
  $Location         = $db_conn->real_escape_string($_POST['Location']);
  $Rules_ID         = $db_conn->real_escape_string($_POST['Rules']);
  if($action == 'Edit') {
    // edit Query
    if( $db_conn->query( " UPDATE Event 
                           SET Title = '$Title', StartDate = '$StartDate', EndDate = '$EndDate', Location = '$Location',
                               Network = '$Network', SeatsOpen = '$SeatsOpen', Rules = '$Rules_ID'
                           WHERE PageID = '$tempID'" 
                       )){header("Location: index.php?page=Admin&subpage=Event#admin_menu");}
  } else {
    // Create Query
    if($db_conn->query("INSERT INTO Event (Title,StartDate,EndDate,Location,SeatsOpen,Rules)
                        VALUES ('$Title', '$StartDate', '$EndDate', '$Location', '$SeatsOpen', '$Rules_ID')")){
      header("Location: index.php?page=Admin&subpage=Event#admin_menu");
    }
  }
}
?>
<form method="post" class="form-group" action="">
  <div class="form-group col-lg-6">
    <label class="control-label" for="Title">Title</label>
    <input required class="form-control" type="text" name="Title" id="Title" value="<?php if(isset($EventExist)){echo $row['Title'];} ?>" maxlength="50"/>
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="StartDate">Start Dato</label>
    <input class="form-control form_datetime" required type="datetime-local" name="StartDate" id="StartDate" value="<?php if(isset($EventExist)){echo $row['StartDate'];} ?>" />
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="EndDate">Slut Dato</label>
    <input class="form-control form_datetime" required type="datetime-local" name="EndDate" id="EndDate" value="<?php if(isset($EventExist)){echo $row['EndDate'];} ?>" />
  </div>
  <div class="form-group col-lg-6">
    <label class="control-label" for="Location">Lokation</label>
    <input class="form-control" required type="text" name="Location" id="Location" value="<?php if(isset($EventExist)){echo $row['Location'];} ?>" />
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="SeatsOpen">SeatsOpen</label>
    <input class="form-control form_datetime" required type="datetime-local" name="SeatsOpen" id="SeatsOpen" value="<?php if(isset($EventExist)){echo $row['SeatsOpen'];} ?>" />
  </div>
  <div class="form-group col-lg-3">
    <label class="control-label" for="Poster">Poster</label>
    <input class="form-control" type="file" id="Poster">
  </div>
  <div class="form-group col-lg-6">
    <label class="control-label" for="Type">Tilføj billet typer</label>
    <div class="form-group">
        <select class="form-control" type="text" placeholder="Type" class="" name="region" id="Type">
        <option></option>
        <?php 
          $TTypeResult = $db_conn->query("SELECT * FROM TicketTypes");
          
          while($TypeRow = $TTypeResult->fetch_assoc()){
            echo "<option value='".$TypeRow['Type']."'>".$TypeRow['Type']."</option>";
          }
        ?>
      </select>
    </div>
    <div class="form-inline">
      <div class="input-group">
        <input class="form-control" type="text" placeholder="150" size="2" class="" name="region" id="TypePrice" />
        <div class="input-group-addon">,-</div>
      </div>
      <div class="input-group">
      <input class="form-control form_datetime" placeholder="dd-mm-yyyy" data-date-format="dd-mm-yyyy hh:ii" type="datetime" size="21" name="region" id="TypeStart" />
      <div class="input-group-addon">&#x1f4c5;</div>
      </div>
      <div class="input-group">
      <input class="form-control form_datetime" placeholder="dd-mm-yyyy" data-date-format="dd-mm-yyyy hh:ii" type="datetime" size="21" name="region" id="TypeEnd" />
      <div class="input-group-addon">&#x1f4c5;</div>
      </div>
    </div>
    &nbsp;
    
    <input type="button" name="add" id="btn_AddToList" value="Tilføj" class="btn btn-success form-control" />
    <select size="10" class="form-control" id="TypeList" multiple="multiple"></select>
    <input type="button" name="add" id="btn_RemoveFromList" value="Fjern" class="btn btn-danger form-control" />
    
    <script type="text/javascript">
      $(function(){
        $("#btn_AddToList").click(function(){
          var type      = $('#Type').val();
          var price     = $('#TypePrice').val();
          var startdate = $('#TypeStart').val();
          var enddate   = $('#TypeEnd').val();
          $('#TypeList').append('<option value="'+type+' '+price+' '+startdate+' '+enddate+'">'+type+' | '+price+',- | '+startdate+' | '+enddate+'</option>');
          
          $('#Type').val('').focus();
          $('#TypePrice').val('');
          $('#TypeStart').val('');
          $('#TypeEnd').val('');
          
        });

        $('#btn_RemoveFromList').click(function(){
          var cr = confirm('Er du sikker på du vil fjerne de valgte fra listen?');
          if(cr == true){
           $('#TypeList > option:selected').each(function(){
            $(this).remove();
          }) 
          }
        });
      });
    </script>
  </div>
  <div class="form-group col-lg-6">
    <label for="RuleSetSelect">Regelsæt (Vælg en)</label>
    <?php 
    $GetRuleSets = $db_conn->query(" SELECT Pages.PageID, Pages.PageTitle, Pages.LastEditedDate 
                                           FROM Pages WHERE Pages.PageTitle like 'Reg%' ORDER BY Pages.LastEditedDate DESC");
    ?>
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
