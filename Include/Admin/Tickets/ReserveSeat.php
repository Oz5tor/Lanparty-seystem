<?php

if (isset($_POST['ReserveSeat'])) {
  # code...
  $SeatNo             = $_POST["SeatNo"];
  $UserIDGettingSeat  = $_POST["UserID"];
  $tempEventID        = $_GLOBAL["EventID"];
  $temptime           = time();
  $year               = date('Y', time());
  
  $SeatTaken  = $db_conn->query("SELECT * FROM Tickets WHERE EventID = '$tempEventID' AND SeatNumber = '$SeatNo' AND RevokeDate IS NULL")->num_rows;
  $Userexists = $db_conn->query("SELECT * FROM Users Where UserID = '$UserIDGettingSeat' Limit 1")->num_rows;
  
  if ($Userexists == 1) {
    if ($SeatTaken == 0) {
      $isUserIDMember = $db_conn->query("SELECT * FROM UserMembership WHERE `Year` = '$year' AND UserID = '$UserIDGettingSeat'")->num_rows;
      switch ($isUserIDMember) {
        case '1':
          $GetTicketPriceIDSQL = $db_conn->query("SELECT * FROM TicketPrices WHERE `Type` = 'Medlem' AND StartTime <= '$temptime' AND EndTime >= '$temptime'");
          if ($row = $GetTicketPriceIDSQL->fetch_assoc()) {
            $PriceID = $row['TicketPriceID'];
          }
          break;
        default:
        $GetTicketPriceIDSQL = $db_conn->query("SELECT * FROM TicketPrices WHERE `Type` = 'Ikke-medlem' AND StartTime <= '$temptime' AND EndTime >= '$temptime'");
        if ($row = $GetTicketPriceIDSQL->fetch_assoc()) {
          $PriceID = $row['TicketPriceID'];
        }
        break;
      }// end of Switch

      # Insert Resevation into DB now that all Checks out
      $db_conn->query("INSERT INTO Tickets (`UserID`, `TicketPriceID`, `OrderedDate`, `EventID`, `SeatNumber`) VALUES
                                           ('$UserIDGettingSeat', '$PriceID', '$temptime', '$tempEventID', '$SeatNo') ");

      echo "<b>Pladsen er reseverert</b>";

    } // end of is seat taken
    else{ echo "pladsen er allrede taget";}
  }// end of user exist
  else {
    echo "brugeren findes ikke";
  }
} // end of POST
?>

<h3>Opret Resevation:</h3>
<p>Gør brug af "Bruger Admin" for at finde brugerens unikke ID, Hvilken billet type og pris vil automastik blive fundet af systemet.</p>
<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group col-lg-3">
    <label class="control-label" for="">Hvilken plads skal reseveres:</label>
    <div class="input-group">
      <input name="SeatNo" required placeholder="123" class="form-control"/>
    </div>
  </div>
  
  <div class="form-group col-lg-2">
    <label class="control-label" for="">
      Bruger ID
    </label>
    <div class="input-group">
      <input type="number" name="UserID" required class="form-control"/>
    </div>
  </div>

  <div class="form-group col-lg-3">
    <label class="control-label" for="">&nbsp;</label>
    <div class="input-group">
        <input class="btn btn-primary" type="submit" value="Resaver Plads" name="ReserveSeat">
      </div>
  </div>
</form>