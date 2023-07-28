<?php
  // Include seatmap //
  include_once 'class/seatmap.php';
  // Get event info //
  $event = $db_conn->query("SELECT * FROM Event ORDER BY EventID DESC LIMIT 1");
  if( $event -> num_rows ) { $eventrows = $event->fetch_assoc(); }
  // Get seatmap info //
  $query = "SELECT Width, SeatString
              FROM Seatmap
              WHERE Seatmap.SeatmapID = " . $eventrows['Seatmap'];
  $result = $db_conn->query($query);
  if ($result -> num_rows) {
    $row = $result->fetch_assoc();
    $width = $row['Width'];
    $fullString = $row['SeatString'];
  }
  // Dynamic color picker things //
  $theColorBegin = 0x00ff00; // Always from green
  $theColorEnd = 0xff0000; // Always to red

  $theR0 = ($theColorBegin & 0xff0000) >> 16;
  $theG0 = ($theColorBegin & 0x00ff00) >> 8;
  $theB0 = ($theColorBegin & 0x0000ff) >> 0;

  $theR1 = ($theColorEnd & 0xff0000) >> 16;
  $theG1 = ($theColorEnd & 0x00ff00) >> 8;
  $theB1 = ($theColorEnd & 0x0000ff) >> 0;

  function interpolate($pBegin, $pEnd, $pStep, $pMax) {
    if ($pBegin < $pEnd) {
      return (($pEnd - $pBegin) * ($pStep / $pMax)) + $pBegin;
    } else {
      return (($pBegin - $pEnd) * (1 - ($pStep / $pMax))) + $pEnd;
    }
  }
?>

<div class="col-lg-12 LanCMScontentbox">
  <div class="row">
    <!-- Basic info -->
    <div class="col-lg-12">
      
        <h1><?php echo $eventrows['Title']; ?></h1>
        <hr> <!-- HORIZONTAL LINE -->
        <div>
          <p><b>Start tidspunkt:</b> <?php echo date("d/m/y - H:i:s",$eventrows['StartDate']); ?>. <b>Slut tidspunkt:</b> <?php echo date("d/m/y - H:i:s",$eventrows['EndDate']); ?></p>
          <p><b>Adresse:</b> <?php echo $eventrows['Location']; ?> <a href="?page=Find Os">Se Map</a></p>
          <p><b>Internet/LAN: </b> <?php echo $eventrows['Network']; ?></p>
          <p><b>Regler: </b> <a href="?page=Regler">Læs dem her</a></p>
          <hr> <!-- HORIZONTAL LINE -->
          <div>
            <!-- Tickets prices -->
            <h2>Billet Priser <a href="?page=Buy" class="btn btn-primary">Køb billet her &raquo;</a></h2>
            <div class="row">
              <?php
              // Get distinct types //
              $DistinctEventPriceTypes = $db_conn->query("
                            SELECT DISTINCT TicketPrices.Type
                            FROM TicketPrices
                            LEFT JOIN TicketTypes
                            ON TicketPrices.Type = TicketTypes.Type
                            WHERE TicketPrices.EventID = " . $eventrows["EventID"] . "
                            ORDER BY TicketTypes.Sort ASC");
              if( $DistinctEventPriceTypes -> num_rows ) {
                while ($type = $DistinctEventPriceTypes->fetch_assoc()) {
                  foreach ($type as $key => $value) {
                    echo "<div class='col-lg-2'><p><b>" . $type["Type"] . ":" . "</p></b></div>";
                    // Get ticket values per type //
                    $SqlPricesQuery = "
                            SELECT *
                            FROM TicketPrices
                            WHERE EventID = " . $eventrows["EventID"] . "
                            AND Type = '" . $type["Type"] . "'
                            ORDER BY Type, StartTime ASC";
                    $SqlPrices = $db_conn->query($SqlPricesQuery);
                    $SqlPricesCount = mysqli_num_rows ($SqlPrices); // Get amount of columns
                      echo "<div class='col-lg-10'>";
                      // Counter for colors //
                      $i = 0;
                      while ($row = mysqli_fetch_assoc($SqlPrices)) {
                        // Calculate column width //
                        $colwidth = 100 / $SqlPricesCount;
                        // Get color values //
                        if ( $SqlPricesCount > 1 ){
                          $theR = round(interpolate($theR0, $theR1, $i, $SqlPricesCount-1));
                          $theG = round(interpolate($theG0, $theG1, $i, $SqlPricesCount-1));
                          $theB = round(interpolate($theB0, $theB1, $i, $SqlPricesCount-1));
                        } elseif ( $SqlPricesCount == 1 ) {
                          $theR = 0;
                          $theG = 255;
                          $theB = 0;
                        }
                        // Create divs //
                        echo "<div style='display: inline-block; background-color: rgb(" . $theR . ", " . $theG . ", " . $theB . "); width: " . $colwidth . "&#37;' class='text-center LanCMSBlack_Border'>" .
                        date("d/m",$row["StartTime"]) . " - " . date("d/m",$row["EndTime"]) . "<br>" . $row["Price"] . ",-" . "</div>";
                        $i++;
                      }
                    echo "</div>";
                  }
                }
              }
              ?>
            </div>
          </div><!-- Tickets prices end -->
          <hr> <!-- HORIZONTAL LINE -->
        </div>
      </div><!-- Basic info end -->
      <!-- Poster -->
    </div>
    <div class="row">
      <div class="col-lg-3">
        <?php
        echo '<img class="img-responsive" src="Images/EventPoster/'.$eventrows['Poster'].'">';
        ?>
      </div><!-- Poster -->
      <div class="col-lg-9">
        <!-- Organizers -->
        <?php
          $orginizorTxt = $db_conn->query("SELECT * FROM Pages Where PageID = '17' LIMIT 1");
          if($row = $orginizorTxt->fetch_assoc()){
            echo $row["Content"];
          }
        ?>
      </div>
    </div><!-- end of first div row -->
  </div>
</div>

<?php
  $event -> close();
?>
