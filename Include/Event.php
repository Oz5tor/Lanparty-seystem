<?php
  // Include seatmap //
  include_once 'class/seatmap.php';
  # Latest event - Get the ID.
  // Get event info //
  $event = $db_conn->query("SELECT e.Title, e.EventID, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.Seatmap, e.Rules FROM Event as e ORDER BY e.EventID DESC LIMIT 1");
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
?>

<div class="col-lg-12 hlpf_contentbox">
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
                      echo "<div class='row'>";
                      // Simple color counter //
                      $counter = 1;
                      while ($row = mysqli_fetch_assoc($SqlPrices)) {
                        // Simple color picker //
                        $color = 'white';
                        if ($counter == 1) { $color = 'lightgreen'; }
                        if ($counter == 2) { $color = 'yellow'; }
                        if ($counter == 3) { $color = 'orange'; }
                        if ($counter == 4) { $color = 'red'; }
                        // Calculate column width //
                        $colwidth = 80 / $SqlPricesCount;
                        // Create divs //
                        echo "<div style='display: inline-table; background-color: " . $color . "; width: " . $colwidth . "%;' class='text-center hlpf_Black_Border'>" . 
                        date("d/m",$row["StartTime"]) . " - " . date("d/m",$row["EndTime"]) . "<br>" . $row["Price"] . ",-" . "</div>";
                        $counter++;
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
          <!-- Seat map (magic) -->
          <div id="map" class="col-lg-12">
            <div id="generated-seat-map"></div>
          </div>
          <div style="float: left;" id="generated-seat-map-legend" class="col-lg-12"></div>
          <div>
            <script src="JS/seat-charts/jquery.seat-charts.min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {
              var sc = $('#generated-seat-map').seatCharts({
                map: [<?php seatmap_generation($fullString, $width) ?>],
                seats: {
                  A: { classes: 'seatStyle_Arkade' },
                  s: { classes: 'seatStyle_Stage' },
                  c: { classes: 'seatStyle_Crew' },
                  k: { classes: 'seatStyle_Kiosk' }
                },
                legend : {
                  node  : $('#generated-seat-map-legend'),
                  items : [
                    [ 'a', 'available', 'Fri plads' ],
                    [ 'c', 'unavailable', 'Crew plads'],
                    [ 's', 'unavailable', 'Scene / Storskærm'],
                    [ 'A', 'unavailable', 'Arkade'],
                    [ 'k', 'unavailable', 'Kiosk'],
                    [ 'a', 'unavailable', 'Optaget' ]
                  ]
                }
              });
              // Make all these seats unavailable.
              // Unless you want people to buy them, ofcourse.
              sc.find('A.available').status('unavailable');
              sc.find('c.available').status('unavailable');
              sc.find('s.available').status('unavailable');
              sc.find('k.available').status('unavailable');
            });
            </script>
          </div><!-- Seat Map end -->
        </div>
      </div><!-- Basic info end -->
      <!-- Poster -->
      <div class="col-lg-3">
        <?php
        echo '<img class="img-responsive" src="Images/EventPoster/'.$eventrows['Poster'].'">';
        ?>
      </div><!-- Poster -->
      <div class="col-lg-3">
        <!-- Arrangør -->
        <h4>Arrangør</h4>
        <p>
          HLParty arrangeres af foreningen Hovedstadens LanParty Forening. Foreningen er en folkeoplysende forening, godkendt i Hillerød kommune. Foreningens formål er (uddrag af vedtægter):
          <blockquote class="hlpf_smallerquote">
            <i>Foreningens formål er at samle unge mennesker, primært i hovedstadsområdet, med interesse for computere og IT, for derved at medvirke til at styrke medlemmernes sociale kompetencer, skabe kontakt på tværs af kommunegrænser, etnicitet, køn og alder og styrke medlemmernes almennyttige IT kundskaber til glæde for den enkelte, såvel som for samfundet.</i>
            <small>Hovedstadens LANParty Forening</small>
          </blockquote>
          Overskud fra et arrangement går til drift af foreningen (f.eks. webhotel, vedligeholdelse og nyinkøb af servere, switche, netværkskabler mv.), samt til at sikre fremtidige arrangementer.
        </p>
        <p>Læs mere om foreningen bag HLParty på adressen <a href="https://hlpf.dk" target="_blank">https://hlpf.dk</a>.</p>
      </div>
    </div><!-- end of first div row -->
  </div>
</div>

<?php
  $event -> close();
?>