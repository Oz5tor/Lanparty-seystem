<?php
  // Include seatmap //
  include_once 'class/seatmap.php';
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
                      echo "<div class='col-lg-10'>";
                      // Counter for colors //
                      $i = 0;
                      while ($row = mysqli_fetch_assoc($SqlPrices)) {
                        // Calculate column width //
                        $colwidth = 100 / $SqlPricesCount;
                        // Create divs //

                        if ( $SqlPricesCount > 1 ){
                          $theR = interpolate($theR0, $theR1, $i, $SqlPricesCount-1);
                          $theG = interpolate($theG0, $theG1, $i, $SqlPricesCount-1);
                          $theB = interpolate($theB0, $theB1, $i, $SqlPricesCount-1);
                        }
                        if ( $SqlPricesCount == 1 ) { 
                          $theVal = 'green';
                        }
                        else { 
                          $theVal = ((($theR << 8) | $theG) << 8) | $theB; 
                        }

                        echo "<div style='display: inline-block; background-color: " . $theVal . "; width: " . $colwidth . "%;' class='text-center hlpf_Black_Border'>" . 
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
          <br>
          <hr>
          <!-- Seat map (magic) -->
          <h2>Pladsoversigt:</h2>
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

<!-- Dynamic colour picker -->
<?
  //$theColorBegin = (isset($_REQUEST['cbegin'])) ? hexdec($_REQUEST['cbegin']) : 0x000000;
  //$theColorEnd = (isset($_REQUEST['cend'])) ? hexdec($_REQUEST['cend']) : 0xffffff;
  //$theNumSteps = (isset($_REQUEST['steps'])) ? intval($_REQUEST['steps']) : 16;

  //$theColorBegin = (($theColorBegin >= 0x000000) && ($theColorBegin <= 0xffffff)) ? $theColorBegin : 0x000000;
  //$theColorEnd = (($theColorEnd >= 0x000000) && ($theColorEnd <= 0xffffff)) ? $theColorEnd : 0xffffff;
  //$theNumSteps = (($theNumSteps > 0) && ($theNumSteps < 256)) ? $theNumSteps : 16;
?>
  <!--
  <form method="GET">
    <table border='1'>
      <tr>
        <td>variable:</td>
        <td>number type</td>
        <td>minimum</td>
        <td>maximum</td>
        <td>value</td>
      </tr>
      <tr>
        <td>color begin:</td>
        <td>hex</td>
        <td>0x000000</td>
        <td>0xFFFFFF</td>
        <td><input name="cbegin" value="<? //printf("%06X", $theColorBegin); ?>"></td>
      </tr>
      <tr>
        <td>color end:</td>
        <td>hex</td>
        <td>0x000000</td>
        <td>0xFFFFFF</td>
        <td><input name="cend" value="<? //printf("%06X", $theColorEnd); ?>"></td>
      </tr>
      <tr>
        <td>number of steps:</td>
        <td>dec</td>
        <td>1</td>
        <td>255</td>
        <td><input name="steps" value="<? //echo $theNumSteps; ?>"></td>
      </tr>
    </table>
    <input type="submit" value="generate color gradient">
  </form>
  -->
<?
  //printf("<p>values are: (color begin: 0x%06X), (color end: 0x%06X), (number of steps: %d)</p>\n", $theColorBegin, $theColorEnd, $theNumSteps);

  //$theR0 = ($theColorBegin & 0xff0000) >> 16;
  //$theG0 = ($theColorBegin & 0x00ff00) >> 8;
  //$theB0 = ($theColorBegin & 0x0000ff) >> 0;

  //$theR1 = ($theColorEnd & 0xff0000) >> 16;
  //$theG1 = ($theColorEnd & 0x00ff00) >> 8;
  //$theB1 = ($theColorEnd & 0x0000ff) >> 0;

  // return the interpolated value between pBegin and pEnd
  //function interpolate($pBegin, $pEnd, $pStep, $pMax) {
    //if ($pBegin < $pEnd) {
      //return (($pEnd - $pBegin) * ($pStep / $pMax)) + $pBegin;
    //} else {
      //return (($pBegin - $pEnd) * (1 - ($pStep / $pMax))) + $pEnd;
    //}
  //}

  // generate gradient swathe now
  //echo "<table width='100%' cellpadding='8' style='border-collapse:collapse'>\n";
  //for ($i = 0; $i <= $theNumSteps; $i++) {
    //$theR = interpolate($theR0, $theR1, $i, $theNumSteps);
    //$theG = interpolate($theG0, $theG1, $i, $theNumSteps);
    //$theB = interpolate($theB0, $theB1, $i, $theNumSteps);

    //$theVal = ((($theR << 8) | $theG) << 8) | $theB;

    //$theTDTag = sprintf("<td bgcolor='#%06X'>", $theVal);
    //$theTDARTag = sprintf("<td bgcolor='#%06X' align='right'>", $theVal);

    /*
    $theFC0Tag = "<font color='#000000'>";
    $theFC1Tag = "<font color='#ffffff'>";
          printf("<tr>$theTDTag$theFC0Tag%d</font></td>$theTDTag$theFC0Tag%d%%</font></td>$theTDARTag$theFC0Tag%d</font></td>$theTDARTag$theFC0Tag%06X</font></td>", $i, ($i/$theNumSteps) * 100, $theVal, $theVal);
    printf("$theTDTag$theFC1Tag%06X</font></td>$theTDTag$theFC1Tag%d</font></td>$theTDARTag$theFC1Tag%d%%</font></td>$theTDARTag$theFC1Tag%d</font></td></tr>\n", $theVal, $theVal, ($i/$theNumSteps) * 100, $i);
    */
  //}
  //echo "</table>\n";
?>