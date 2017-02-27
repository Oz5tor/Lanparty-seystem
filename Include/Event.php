<?php
  # Latest event - Get the ID.
  $event = $db_conn->query("SELECT e.Title, e.EventID, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.Seatmap, e.Rules FROM Event as e ORDER BY e.EventID DESC LIMIT 1");
  if( $event -> num_rows ) { $eventrows = $event->fetch_assoc(); }
?>

<div class="col-lg-12 hlpf_contentbox"> <!-- Ret class til-->
  <div class="row">
    <!-- Basic info -->
    <div class="col-lg-12">
      <div class="col-lg-9">
        <h1><?php echo $eventrows['Title']; ?></h1>
        <hr>
        <!-- ============== -->
        <div>
          <p><b>Start tidspunkt:</b> <?php echo date("d/m/y - H:i:s",$eventrows['StartDate']); ?>. <b>Slut tidspunkt:</b> <?php echo date("d/m/y - H:i:s",$eventrows['EndDate']); ?></p>
          <p><b>Adresse:</b> <?php echo $eventrows['Location']; ?> <a href="#">Se Map</a></p>
          <p><b>Internet/LAN: </b> <?php echo $eventrows['Network']; ?></p>
          <p><b>Regler: </b> <a href="?page=<?php echo $eventrows['Rules']; ?>">Læs dem her</a></p>
          <hr>
          <!-- Tickets prices -->
          <div>
            <h2>Billet Priser:</h2>
            <div class="row">
              <?php
              // Get distinct types //
              $DistinctEventPriceTypes = $db_conn->query("
                          SELECT DISTINCT tp.Type 
                          FROM TicketPrices as tp 
                          LEFT JOIN TicketTypes as tt 
                          ON tp.Type = tt.Type 
                          WHERE tp.EventID = " . $eventrows["EventID"] . "
                          ORDER BY tt.Sort ASC");
              if( $DistinctEventPriceTypes -> num_rows ) {
                while ($type = $DistinctEventPriceTypes->fetch_assoc()) {
                  foreach ($type as $key => $value) {
                    echo "<div class='col-lg-3'><p><b>" . $type["Type"] . ":" . "</p></b></div>";
                    // Get ticket values per type //
                    $SqlPricesQuery = "
                              SELECT * 
                              FROM TicketPrices as tp 
                              WHERE tp.EventID = " . $eventrows["EventID"] . " 
                              AND tp.Type = '" . $type["Type"] . "' 
                              ORDER BY tp.Type, tp.StartTime ASC";
                    $SqlPrices = $db_conn->query($SqlPricesQuery);
                      echo "<div class='row'>";
                      while ($row = mysqli_fetch_array($SqlPrices)) {
                        echo "<div style='border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; background-color: lightgreen;' class='col-lg-2 text-center'>" . 
                        date("d/m",$row["StartTime"]) . " - " . date("d/m",$row["EndTime"]) . "<br>" . $row["Price"] . ",-" . "</div>";
                      }
                    echo "</div>";
                  }
                }
              }
              ?>
            </div>
          </div><!-- Tickets prices end -->
          <br>
          <hr>
          <!-- Seat map -->
          <div>
            <img src="Images/2017-02-23%2013_58_43-HLParty%20-%20Admin%20-_%20Seatmap.png">
          </div><!-- Seat Map end -->
        </div>
      </div><!-- Basic info end -->
      <!-- Poster -->
      <div class="col-lg-3">
        <?php
        echo '<img class="img-responsive" src="Images/EventPoster/'.$eventrows['Poster'].'">';
        ?>
      </div><!-- Poster -->
    </div><!-- end of first div row -->
  </div>
</div>

<?php
  $event -> close();
?>